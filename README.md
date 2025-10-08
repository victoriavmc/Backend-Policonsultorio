# 🩺 **Sistema de Gestión para Policonsultorio — PULMOLAB**

---

## 📖 Descripción del Proyecto

La elección del proyecto se fundamenta en el inicio de la materia **Práctica Profesional Supervisada (PPS)**, en la cual el grupo fue designado para el desarrollo de un sistema de gestión para el policonsultorio **Pulmolab**.

No obstante, la consigna establecida por el docente establece que el alcance del trabajo debe orientarse a un policonsultorio **en términos generales**, de manera que la solución resultante pueda **adaptarse a distintos consultorios**.

En este marco, se acordó trabajar sobre una **base común**, contemplando los procesos esenciales que caracterizan a cualquier policonsultorio (gestión de turnos, pacientes, tratamientos, diagnósticos, entre otros).

El caso particular de Pulmolab presenta un **requerimiento diferencial**: la incorporación de módulos específicos para **imágenes médicas con Inteligencia Artificial (imagenesIA)** y **observaciones automáticas asistidas por IA (observacionesIA)** dentro de los diagnósticos.

Este planteo permite cumplir con la consigna académica y, a su vez, **aportar valor agregado** mediante la integración de tecnologías avanzadas que potencian la calidad del servicio médico.

---

## 🧩 Identificación de Requisitos Funcionales

El sistema Pulmolab se estructura en módulos que reflejan los procesos esenciales de un policonsultorio moderno.  
Cada módulo se orienta a optimizar la gestión médica, administrativa y de soporte mediante funciones específicas.

| Módulo                       | Funcionalidad                     | Descripción Breve                                                                           |
| ---------------------------- | --------------------------------- | ------------------------------------------------------------------------------------------- |
| Autenticación                | Iniciar Sesión / Recuperar Cuenta | Permite el acceso seguro de médicos y usuarios mediante credenciales cifradas.              |
| Sistema de Notificaciones    | Alertas y Frecuencia              | Facilita la configuración y recepción de notificaciones sobre turnos y eventos del sistema. |
| Gestión de Turnos            | Creación y Modificación de Turnos | Administra la agenda médica, permitiendo crear, editar y visualizar turnos.                 |
| Gestión de Horarios          | Horarios Laborales                | Define y gestiona los días y horarios de atención de cada profesional.                      |
| Gestión de Pacientes         | Datos del Paciente                | Registra, modifica y filtra información personal y médica de pacientes.                     |
| Obras Sociales               | Administración de Obras Sociales  | Gestiona las distintas coberturas disponibles y sus convenios asociados.                    |
| Tratamientos                 | Planes de Tratamiento             | Permite registrar, actualizar y consultar tratamientos médicos activos.                     |
| Seguimiento de Pagos         | Registro y Listado de Pagos       | Administra el estado de pagos, su modalidad y exportación en PDF/Excel.                     |
| Formularios PDF              | Formularios Médicos               | Permite agregar y modificar formularios clínicos digitales.                                 |
| Recetas                      | Recetas Digitales                 | Genera y gestiona recetas médicas electrónicas vinculadas a pacientes.                      |
| Diagnósticos                 | Diagnóstico Médico                | Registra, actualiza y consulta diagnósticos clínicos.                                       |
| Solicitudes de Tratamiento   | Solicitudes Médicas               | Gestiona las solicitudes de tratamientos derivados de diagnósticos.                         |
| Historial Clínico            | Registro Clínico                  | Mantiene la trazabilidad de los antecedentes médicos de cada paciente.                      |
| Consultas                    | Consultas Médicas                 | Permite registrar y administrar las consultas realizadas.                                   |
| Auditoría                    | Registro de Actividades           | Lleva control de todas las acciones realizadas en el sistema.                               |
| ESPECIFICO PULMOLAB          | ESPECIFICO PULMOLAB               | ESPECIFICO PULMOLAB                                                                         |
| Inteligencia Artificial (IA) | Imágenes y Observaciones IA       | Analiza imágenes médicas y genera observaciones automáticas asistidas por IA.               |

---

## ⚙️ **Configuración del Entorno de Desarrollo**

### 🧭 1. Control de Versiones

El sistema de control de versiones empleado es **Git**, en conjunto con la plataforma **GitHub**, lo que permitió mantener un registro histórico del código, trabajar en ramas por funcionalidad y coordinar el trabajo colaborativo del equipo.

```bash
git config --global user.name "usuario1"
git config --global user.email "usuario1@gmail"
git --version
```

---

### 🖥️ 2. Entorno de Desarrollo

Visual Studio Code fue el entorno seleccionado, complementado con extensiones que optimizan el flujo de trabajo:

-   GitHub Pull Requests and Issues
-   PHP Intelephense
-   Laravel Blade Snippets
-   React Developer Tools
-   Postman (para pruebas de API)
-   Error Lens
-   Path IntelliSense
-   Laravel Artisan

---

### 🧩 3. Instalación de PHP y Configuración del Archivo php.ini

El backend se desarrolla en PHP 8.x bajo Laravel.
Para garantizar compatibilidad y soporte de funciones críticas, se habilitaron las siguientes extensiones dinámicas:

```bash
; Dynamic Extensions
extension=zip
extension=openssl
extension=fileinfo
extension=curl
extension=gd
extension=mbstring
extension=exif
extension=mysqli
extension=pdo_mysql

; File Uploads
file_uploads = On
upload_max_filesize = 200M
post_max_size = 210M
max_file_uploads = 20
```

---

### 📦 4. Dependencias del Proyecto

Una vez clonado el repositorio, no es necesario instalar dependencias manualmente.
Basta con ejecutar:

```bash
composer update
php artisan key:generate
```

Esto descargará automáticamente todas las dependencias definidas en composer.json.

Dependencias Incluidas:

-   Laravel Sanctum → Autenticación por tokens y API segura
-   Barryvdh/Dompdf → Exportación de reportes en PDF
-   Laravel Excel → Exportación de datos a XLSX
-   Intervention/Image → Conversión de imágenes a WebP
-   Jenssegers/Mongodb → Driver MongoDB para Laravel
-   Larabel Lang → Modifica el idioma

---

### 🌐 5. Configuración del Archivo .env

Modificar las variables según el entorno:

```bash
APP_NAME=Pulmolab
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pulmolab
DB_USERNAME=root
DB_PASSWORD=
```

---

### 🧱 6. Estructura de Modelos y Controladores

Tener en cuenta que:

| Concepto    | Convención | Ejemplo             |
| ----------- | ---------- | ------------------- |
| Modelo      | Singular   | `Usuario`           |
| Colección   | Plural     | `usuarios`          |
| Controlador | Plural     | `UsuarioController` |

De manera automatica el comando para la creación de modelo, migración y controlador es:

```bash
php artisan make:model Paciente -m --api
```

---

### 🧾 7. Rutas API

Las rutas están definidas en:

```bash
routes/api.php
```

Ejemplo

```bash
Route::apiResource('pacientes', PacienteController::class);
```

Esto genera automáticamente las rutas RESTful:

-   GET /pacientes → index
-   POST /pacientes → store
-   GET /pacientes/{id} → show
-   PUT/PATCH /pacientes/{id} → update
-   DELETE /pacientes/{id} → destroy

Iniciar servidor:

```bash
php artisan serve
```

---

## 🔬 Pruebas de API — Postman

Para la validación y testeo de endpoints, se emplea **Postman**, herramienta que permite:

-   Probar los métodos `GET`, `POST`, `PUT` y `DELETE` de cada recurso.
-   Simular autenticación por tokens mediante **Laravel Sanctum**.
-   Verificar respuestas JSON y estados HTTP.
-   Exportar y documentar colecciones de pruebas automatizadas.

---

## 📑 ENCARGADOS DEL BACKEND

<table>
  <tr>
    <!-- Añadir más colaboradores -->
    <!--  -->
    <td align="center">
      <a href="https://github.com/victoriavmc">
        <img src="https://avatars.githubusercontent.com/u/94030658?v=4" width="100" alt="Avatar de VictoriaVMC"><br />
        <sub><b>Victoria VMC</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/SantiAranda">
        <img src="https://avatars.githubusercontent.com/u/134805510?v=4" width="100" alt="IMAGEN de SantiAranda"><br/>
        <sub><b>SantiAranda</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/SharkDario">
        <img src="https://avatars.githubusercontent.com/u/116774936?v=4" width="100" alt="IMAGEN de SharkDario"><br/>
        <sub><b>SharkDario</b></sub>
      </a>
    </td>
  </tr>
</table>

---

## 🧠 Conclusión

Pulmolab unifica en una única plataforma la gestión integral de un policonsultorio, integrando funcionalidades de atención médica, administración, seguimiento de pacientes y soporte con inteligencia artificial.  
Su enfoque modular, documentado y extensible garantiza un desarrollo sostenible, una integración ágil con el frontend y la posibilidad de escalar el sistema hacia nuevas áreas clínicas o tecnológicas.

---
