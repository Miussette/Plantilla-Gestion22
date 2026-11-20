# Plantilla para Moodle · Aula Verde

Paquete independiente para **Moodle 5.3**, la versión de este proyecto. Puedes sacar esta carpeta de la raíz y llevarla a otra instalación Moodle 5.3. No incluye Moodle completo, base de datos, usuarios, credenciales ni contenidos del curso original. La carpeta anterior `plantilla-cursos` corresponde a la aplicación Node independiente; esta versión funciona dentro de Moodle.

## Qué incluye

- `theme/aulaverde`: tema hijo de Boost. Aplica la paleta verde, blanca y gris al aula, navegación y acceso nativo de Moodle. Mantiene los controles de edición de Boost.
- `local/plantillacursos`: generador de cursos reales con bienvenida, entre 1 y 12 módulos, páginas de contenido y cierre. El curso nace oculto; los contenidos se editan con el editor de Moodle.
- `INSTALAR-LOCAL.ps1`: instalación en el entorno Docker del proyecto original; activa el tema para el sitio y crea un ejemplo oculto. No modifica los contenidos de cursos anteriores. El cambio de tema afecta la apariencia del sitio completo.

## Verlo en este proyecto

Con Docker Desktop iniciado, ejecuta desde PowerShell:

```powershell
powershell -ExecutionPolicy Bypass -File .\plantilla-moodle\INSTALAR-LOCAL.ps1
```

Abre **http://localhost:8080**, entra con tu cuenta Moodle y abre la dirección del curso que imprime el instalador. La contraseña del administrador local sigue siendo la de `MOODLE_ADMIN_PASSWORD` del `.env` original; este paquete no copia ni cambia esa contraseña.

Para crear cursos nuevos: **http://localhost:8080/local/plantillacursos/index.php**. También aparece en Administración del sitio → Cursos → Plantillas de cursos para administradores.

## Editar y reutilizar

1. Entra al curso y activa **Modo de edición**.
2. Edita el nombre y resumen de cada sección para cambiar objetivos y bienvenida. El resumen de bienvenida contiene el bloque verde.
3. En cada página de contenido, usa **Configuración** / **Editar ajustes** para cambiar texto e insertar imágenes, videos o enlaces con el editor de Moodle.
4. Con **Añadir una actividad o un recurso** incorpora archivos, tareas, cuestionarios, foros, etc. Todos usan las herramientas nativas.
5. En la configuración del curso ajusta nombre, imagen y visibilidad. Cuando esté listo, cambia la visibilidad a **Mostrar**.
6. Genera otro curso desde el formulario, o reutiliza un curso ya personalizado mediante **Reutilización del curso → Copia de seguridad** y **Restaurar**. Para compartir solo una plantilla, excluye los usuarios y sus datos de la copia `.mbz`.

## Usuarios, login y permisos

Se usa el login normal `/login/index.php` y la base de datos de Moodle, sin otro sistema de cuentas. El administrador crea cuentas con autenticación **Cuentas manuales**. Este paquete no habilita registro público.

Para que una persona cree sus propios cursos, asigna el rol **Creador de cursos** en la categoría correspondiente. El generador exige `moodle/course:create` y comprueba la categoría tanto al abrir como al enviar el formulario. Usa el rol configurado en Moodle para los creadores en cursos nuevos (`creatornewroleid`), de modo que puedan editar su curso. Para editar un curso existente, matricula a la persona como **Profesor con permiso de edición**. El acceso de alumnos y las matrículas se administran con Moodle.

## Instalar en otro Moodle

1. Comprueba Moodle 5.3 con Boost y el recurso Página habilitados. Haz una copia de seguridad del sitio antes de instalar complementos.
2. Copia `theme/aulaverde` dentro del directorio `theme/` de Moodle y `local/plantillacursos` dentro de `local/`. En Moodle 5.3 corresponden normalmente a `public/theme/` y `public/local/`.
3. Entra como administrador a **Administración del sitio → Notificaciones** y completa la instalación de ambos complementos.
4. En **Apariencia → Temas → Selector de temas**, selecciona **Aula Verde**. Esto incluye el estilo del login.
5. Abre `TU_MOODLE/local/plantillacursos/index.php` para generar el primer curso.

No uses `INSTALAR-LOCAL.ps1` para otra instalación: está preparado para el Docker del proyecto original. No es necesario copiar la carpeta Node ni instalar npm. La carpeta del paquete es una fuente para distribución; el código instalado se ejecuta dentro de los directorios de Moodle.

## Personalizar el diseño

### Editar CSS desde Moodle (recomendado)

Abre **Administración del sitio → Apariencia → Temas → Aula Verde** y usa el campo **CSS personalizado de la plantilla**. En este proyecto el enlace directo es **http://localhost:8080/admin/settings.php?section=themesettingaulaverde**. Requiere una cuenta administradora. Guarda los cambios y recarga el curso para verlos; guardar renueva la caché del tema. No necesitas reinstalarlo.

Por ejemplo, pega estas reglas para cambiar las esquinas y el fondo de la bienvenida:

```css
.aula-hero {
    background: linear-gradient(120deg, #00652e, #0bb523);
    border-radius: 8px;
}
.aula-card {
    border-radius: 8px;
    border-color: #0bb523;
}
```

### Editar un archivo CSS

El archivo **`theme/aulaverde/custom.css`** está preparado para tus cambios y contiene ejemplos comentados de navegación, bienvenida, tarjetas y login. Se carga después del diseño base. Si editas la copia de esta carpeta, ejecuta de nuevo `INSTALAR-LOCAL.ps1` para copiarla al Moodle local; el instalador también renueva la caché. En otro servidor copia el archivo al tema instalado y purga las cachés desde Administración del sitio → Desarrollo → Purgar cachés.

El CSS guardado en el campo de Moodle se aplica al final y tiene prioridad cuando los selectores tienen la misma especificidad. Ese campo se guarda en la base de datos: para llevar sus cambios a otra instalación, cópialos a `custom.css` o al campo del otro Moodle. Los cambios del tema afectan a todos los cursos que lo utilizan. Para un curso concreto, limita las reglas con `body.course-3` (sustituye 3 por el ID del curso).

En **Apariencia → Temas → Aula Verde**, cambia el color de los botones y añade SCSS adicional. Para cambiar toda la paleta, edita `theme/aulaverde/scss/aula.scss`: verde principal `#0bb523`, verde oscuro `#00652e`, interacción `#008d26`, bordes `#dce0df`, texto `#4f565d`. Después purga las cachés de Moodle. El color de botones predeterminado es más oscuro para mejorar la lectura sobre blanco.

El marcado inicial de las secciones está en `local/plantillacursos/classes/local/builder.php`. Cambiarlo afecta a cursos creados posteriormente; los existentes se editan desde Moodle. Para desarrollar en este proyecto, vuelve a copiar los archivos con el instalador; el ejemplo existente no se vuelve a crear.

Si prefieres aplicar el tema solo a algunos cursos, habilita **Permitir temas por curso** en Moodle y selecciona Aula Verde en la configuración de esos cursos. El generador lo selecciona automáticamente cuando esta opción ya está habilitada. El login utiliza siempre el tema del sitio.

Licencia: GNU GPL v3 o posterior, compatible con Moodle. El tema hereda las plantillas de Boost; no incluye tutor IA ni material temático del curso original.
