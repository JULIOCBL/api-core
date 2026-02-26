<?php

namespace Src\Permissions\Domain\Services;

/**
 * Servicio de dominio para transformar permisos planos
 * en una estructura jerárquica de módulos y acciones.
 */
class PermissionsTreeService
{
    /**
     * Construye el árbol de permisos y separa llaves de módulos/acciones.
     *
     * @param array<int, array<string, mixed>> $records
     * @param array<int, string|null> $modules
     * @param array<int, string|null> $actions
     * @param bool $only_modules
     * @return array<int, array<string, mixed>>
     */
    public static function buildPermissionsTree(array $records, array &$modules, array &$actions, bool $only_modules = false): array
    {
        $items_by_id = [];

        foreach ($records as $record) {
            if (($record['status'] ?? 0) !== 1) {
                continue;
            }

            if (isset($record['id'], $record['parent_id']) && $record['id'] === $record['parent_id']) {
                continue;
            }

            $record['children'] = [
                'actions' => [],
                'modules' => [],
            ];

            $items_by_id[$record['id']] = $record;
        }

        $tree = [];

        foreach ($items_by_id as $item_id => &$item) {
            if ($item['parent_id'] === null && $item['type'] === 'MODULE') {
                $tree[] = &$item;
                $modules[] = $item['key'] ?? null;
                continue;
            }

            if (!isset($items_by_id[$item['parent_id']])) {
                continue;
            }

            $parent = &$items_by_id[$item['parent_id']];
            if (($parent['status'] ?? 0) !== 1) {
                continue;
            }

            if ($item['type'] === 'MODULE') {
                $parent['children']['modules'][] = &$item;
                $modules[] = $item['key'] ?? null;
            } elseif (!$only_modules && $item['type'] === 'ACTION' && $parent['type'] === 'MODULE') {
                $parent['children']['actions'][] = &$item;
                $actions[] = $item['key'] ?? null;
            }
        }

        $sort_children = function (array &$items) use (&$sort_children): void {
            foreach ($items as &$item) {
                usort($item['children']['modules'], function (array $left, array $right): int {
                    return ($left['order'] ?? 0) <=> ($right['order'] ?? 0);
                });

                usort($item['children']['actions'], function (array $left, array $right): int {
                    return ($left['order'] ?? 0) <=> ($right['order'] ?? 0);
                });

                $sort_children($item['children']['modules']);
            }
        };

        $sort_children($tree);

        if (!$only_modules) {
            $filter_empty_modules = function (array &$items) use (&$filter_empty_modules): void {
                $items = array_values(array_filter($items, function (array $item) use (&$filter_empty_modules): bool {
                    $child_modules = $item['children']['modules'] ?? [];
                    $filter_empty_modules($child_modules);
                    $item['children']['modules'] = $child_modules;
                    return true;
                }));
            };

            $filter_empty_modules($tree);
        }

        return array_values($tree);
    }

    /**
     * Recorre el árbol y marca cada nodo como habilitado o deshabilitado
     * según el conjunto de llaves permitido.
     *
     * @param array<int, array<string, mixed>> $tree
     * @param array<int, string> $keys
     * @return array<int, array<string, mixed>>
     */
    public static function buildPermissionsWithDisabledKeys(array $tree, array $keys): array
    {
        $result = [];

        foreach ($tree as $item) {
            $new_item = $item;
            $new_item['status'] = in_array($new_item['key'] ?? null, $keys, true) ? 1 : 0;

            if (!empty($new_item['children']['actions'])) {
                $new_item['children']['actions'] = self::buildPermissionsWithDisabledKeys($new_item['children']['actions'], $keys);
            }

            if (!empty($new_item['children']['modules'])) {
                $new_item['children']['modules'] = self::buildPermissionsWithDisabledKeys($new_item['children']['modules'], $keys);
            }

            $result[] = $new_item;
        }

        return $result;
    }

    /**
     * Filtra el árbol dejando únicamente nodos cuyas llaves están habilitadas.
     *
     * @param array<int, array<string, mixed>> $tree
     * @param array<int, string> $enabled_permission_keys
     * @return array<int, array<string, mixed>>
     */
    public static function filterPermissionsTree(array $tree, array $enabled_permission_keys): array
    {
        $filtered = [];

        foreach ($tree as $item) {
            if (!in_array($item['key'] ?? null, $enabled_permission_keys, true)) {
                continue;
            }

            $item['children']['modules'] = self::filterPermissionsTree(
                $item['children']['modules'] ?? [],
                $enabled_permission_keys
            );

            $filtered[] = $item;
        }

        return $filtered;
    }

    /**
     * Extrae las llaves efectivamente habilitadas respetando herencia de estado
     * entre padre e hijos.
     *
     * @param array<int, array<string, mixed>> $tree
     * @param bool $parent_is_disabled
     * @return array<int, string>
     */
    public static function getEnabledPermissionKeys(array $tree, bool $parent_is_disabled = false): array
    {
        $keys = [];

        foreach ($tree as $node) {
            $status = (int) ($node['status'] ?? 1);
            $key = $node['key'] ?? null;
            $current_disabled = $parent_is_disabled || $status === 0;

            if (!$current_disabled && $key !== null) {
                $keys[] = $key;
            }

            if (!isset($node['children']) || !is_array($node['children'])) {
                continue;
            }

            if (!empty($node['children']['modules']) && is_array($node['children']['modules'])) {
                $keys = array_merge(
                    $keys,
                    self::getEnabledPermissionKeys($node['children']['modules'], $current_disabled)
                );
            }

            if (!empty($node['children']['actions']) && is_array($node['children']['actions'])) {
                $keys = array_merge(
                    $keys,
                    self::getEnabledPermissionKeys($node['children']['actions'], $current_disabled)
                );
            }
        }

        return $keys;
    }
}
