# VinculaTEC

## Sistema de Vinculación y Gestión de Residencias Profesionales

VinculaTEC es una plataforma web centralizada diseñada específicamente para el Instituto Tecnológico de La Laguna. Su objetivo principal es optimizar, administrar y automatizar los procesos relacionados con la asignación de residencias profesionales y la bolsa de trabajo, operando como un puente de comunicación digital estructurado entre la comunidad estudiantil, los egresados y el sector productivo.

---

## Características y Funcionalidades Principales

El sistema está diseñado bajo una arquitectura modular y cuenta con flujos de trabajo específicos para distintos tipos de usuarios:

### Gestión de Roles y Control de Acceso (RBAC)

- **Administradores:**  
  Control total sobre el sistema, validación de empresas, gestión de expedientes de alumnos y supervisión de los procesos de vinculación.

- **Empresas:**  
  Capacidad de registrar su perfil corporativo, publicar convocatorias para residencias profesionales, ofertar vacantes de empleo y gestionar las postulaciones recibidas.

- **Residentes (Estudiantes):**  
  Acceso a un catálogo de proyectos y vacantes, postulación directa a convocatorias y seguimiento del estado de sus solicitudes.

- **Egresados:**  
  Acceso exclusivo a la bolsa de trabajo y oportunidades laborales acordes a su perfil profesional.

### Sistema de Convocatorias

- Creación, edición y publicación  
- Cierre automatizado o manual de ofertas  

### Seguridad

- Manejo de sesiones  
- Sanitización de datos de entrada  
- Control de acceso a rutas  

---

## Arquitectura y Estructura del Proyecto

El repositorio sigue una separación estricta entre la lógica de negocio y los archivos expuestos públicamente para mejorar la seguridad y la mantenibilidad del código.

### `public/`

Directorio raíz del servidor web (DocumentRoot).

Contiene:
- HTML  
- CSS  
- JavaScript  
- Imágenes  

> Ningún archivo sensible debe residir aquí.

---

### `src/`

Directorio principal del código fuente backend.

Contiene:
- Controladores  
- Modelos  
- Lógica de negocio  
- Acceso a base de datos  

---

### `composer.json / composer.lock`

Archivos de configuración de Composer:

- Gestión de dependencias  
- Autoloading (PSR-4)  

---

### `.gitattributes / .gitignore`

Configuración de control de versiones:

- Exclusión de `vendor/`  
- Exclusión de `.env`  
- Exclusión de configuraciones locales  

---

## Stack Tecnológico

- **Backend:** PHP (7.x / 8.x)  
- **Gestor de Dependencias:** Composer  
- **Base de Datos:** Oracle Cloud Database  
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)  

---

## Requisitos Previos

Para desplegar este proyecto en un entorno local:

1. Servidor web (Apache/Nginx) con soporte PHP  
2. PHP >= 7.4 (recomendado 8.x)  
3. Extensiones:
   - `oci8`
   - `pdo_oci`
4. Composer instalado  
5. Git  
6. Credenciales de Oracle Cloud / Wallet  

---
###  Clonar el repositorio

```bash
git clone https://github.com/MiguelAGDev/VinculaTEC.git
cd VinculaTEC
