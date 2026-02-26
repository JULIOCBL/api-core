# Cambios Implementados

## 1. Modulo Companies (Hexagonal)

### Que se creo
- Flujo completo en arquitectura hexagonal para companias:
  - Crear compania
  - Editar compania
  - Listado paginado
  - Selector sin paginacion

### Donde esta
- Aplicacion:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Application/UseCases/CreateCompanyUseCase.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Application/UseCases/UpdateCompanyUseCase.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Application/UseCases/PaginateCompaniesUseCase.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Application/UseCases/GetCompaniesSelectorUseCase.php`
- Dominio:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Domain/Contracts/CompanyRepositoryInterface.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Domain/Entities/Company.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Domain/Entities/CompanySelectorItem.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Domain/ValueObjects/PaginatedCompanies.php`
- Infraestructura:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Persistence/EloquentCompanyRepository.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Controllers/CreateCompanyController.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Controllers/UpdateCompanyController.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Controllers/PaginateCompaniesController.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Controllers/GetCompaniesSelectorController.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Requests/CreateCompanyRequest.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Requests/UpdateCompanyRequest.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Requests/PaginateCompaniesRequest.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Presenters/CompanyResponsePresenter.php`

### Que problema resuelve
- Estandariza CRUD base de companias en capas separadas.
- Evita logica de negocio en controladores.
- Permite evolucionar reglas y filtros sin acoplar la capa HTTP.

### Como se usa
- Rutas:
  - `POST /api/v1/companies`
  - `PATCH /api/v1/companies/{company_id}`
  - `GET /api/v1/companies`
  - `GET /api/v1/companies/selector`

### Ejemplo de request/response
- `GET /api/v1/companies?page=1&per_page=15`
- Respuesta paginada con estructura tipo Laravel:
  - `data`
  - `links`
  - `meta`

### Notas tecnicas y limites
- `per_page=-1` trae todos los registros en una sola pagina.
- Selector devuelve data simple para combos (`id`, `commercial_name`).

---

## 2. Reglas de unicidad de commercial_name

### Que se creo
- Validaciones para `commercial_name` en create y update.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Requests/CreateCompanyRequest.php`
- `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Http/Requests/UpdateCompanyRequest.php`

### Que problema resuelve
- Evita duplicados activos por nombre comercial.
- Permite reuso si el registro previo esta eliminado (soft delete).
- En edicion permite mantener el mismo nombre del propio registro.

### Como se usa
- `POST /companies`: valida unico activo.
- `PATCH /companies/{id}`: valida unico activo ignorando el id actual.

### Notas tecnicas y limites
- Basado en `deleted_at` nulo para considerar registros activos.

---

## 3. Modelo Eloquent compartido de Company

### Que se creo
- Modelo Eloquent de compania movido a Shared para reutilizacion interna del proyecto.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/src/Shared/Infrastructure/Persistence/Eloquent/Models/Company.php`

### Que problema resuelve
- Evita dependencia de `app/Models` dentro de modulos.
- Centraliza persistencia compartida para multiples capas/modulos.

### Como se usa
- Consumido por repositorios/seeders mediante namespace Shared.

---

## 4. Builder de consulta para Companies

### Que se creo
- Clase builder para construir consulta base y derivar variantes.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Persistence/Builders/CompanyDataQueryBuilder.php`

### Que problema resuelve
- Unifica origen de datos para paginacion/selector.
- Facilita agregar nuevos metodos de obtencion en el futuro.

### Como se usa
- Repositorio delega en builder:
  - `paginate(...)`
  - `selector()`

### Notas tecnicas y limites
- Ordenamiento simple por request (`order_by`, `order_direction`).
- Alias soportado: `company_name -> commercial_name`.

---

## 5. Trait reutilizable de filtros

### Que se creo
- Trait compartido con filtros genericos para queries Eloquent.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/src/Shared/Infrastructure/Persistence/Eloquent/Traits/AppliesQueryFilters.php`

### Que problema resuelve
- Reutiliza logica de filtros entre endpoints.
- Evita duplicacion en repositorios/builders.

### Como se usa
- Metodos disponibles:
  - `applyExactFilters(...)`
  - `applyLikeFilters(...)`
  - `applyRangeFilters(...)`
  - `applySearchFilter(...)`
  - `applySortFilter(...)`

### Notas tecnicas y limites
- Ordenamiento actual es uno por request (no multi-order).

---

## 6. Modulo Permissions (base)

### Que se creo
- Base de permisos separada por capas:
  - Contrato de repositorio
  - Servicio de arbol
  - Caso de uso para modulos asignados por plataforma
  - Repositorio Eloquent con cache

### Donde esta
- Contratos:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Permissions/Domain/Contracts/PermissionInterface.php`
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Permissions/Application/Contracts/GetAssignedModulesByPlatformInterface.php`
- Dominio:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Permissions/Domain/Services/PermissionsTreeService.php`
- Aplicacion:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Permissions/Application/UseCases/GetAssignedModulesByPlatform.php`
- Infraestructura:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Permissions/Infrastructure/Persistence/EloquentPermissionRepository.php`

### Que problema resuelve
- Centraliza reglas de arbol y asignacion de permisos por plataforma/rol/compania.
- Deja login libre de logica de permisos.

### Como se usa
- Inyeccion via interfaz `GetAssignedModulesByPlatformInterface`.

### Notas tecnicas y limites
- Actualmente maneja estructuras en arrays.
- Recomendada evolucion futura: DTO/VO/entidades tipadas para dominio.

---

## 7. DI / Service Provider

### Que se creo
- Bindings de interfaces a implementaciones.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/src/Core/Infrastructure/Providers/AppServiceProvider.php`

### Que problema resuelve
- Permite desacoplar dependencias por puertos.

### Como se usa
- Registros activos:
  - `CompanyRepositoryInterface -> EloquentCompanyRepository`
  - `PermissionInterface -> EloquentPermissionRepository`
  - `GetAssignedModulesByPlatformInterface -> GetAssignedModulesByPlatform`

---

## 8. Comando de carga de companias (fuera de seeders)

### Que se creo
- Comando Artisan para crear/actualizar lote de companias.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/src/Companies/Infrastructure/Console/Commands/CreateCompaniesBatch.php`
- Registro de comando:
  - `/Users/jcbl/Archivos/GitHub/api-core/src/Core/Infrastructure/Console/Kernel.php`

### Que problema resuelve
- Evita meter cargas masivas en seeders cuando no se desea.

### Como se usa
- `php artisan companies:create-batch`
- `php artisan companies:create-batch 50`

---

## 9. Postman actualizado

### Que se creo
- Coleccion y environment con endpoints de Companies.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/docs/postman/api-core.companies.postman_collection.json`
- `/Users/jcbl/Archivos/GitHub/api-core/docs/postman/api-core.local.postman_environment.json`

### Que problema resuelve
- Acelera pruebas y documentacion compartida con frontend.

### Como se usa
- Importar ambos JSON en Postman.
- Seleccionar environment local.

---

## 10. Migracion de Vite a Webpack Mix

### Que se creo
- Cambio de pipeline de assets a Webpack Mix.

### Donde esta
- `/Users/jcbl/Archivos/GitHub/api-core/webpack.mix.js`
- `/Users/jcbl/Archivos/GitHub/api-core/postcss.config.cjs`
- `/Users/jcbl/Archivos/GitHub/api-core/package.json`
- Vista actualizada:
  - `/Users/jcbl/Archivos/GitHub/api-core/resources/views/welcome.blade.php`

### Que problema resuelve
- Ajusta stack de build solicitado para entorno API/proyecto actual.

### Como se usa
- `npm install`
- `npm run dev`
- `npm run build`

