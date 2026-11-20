$ErrorActionPreference = 'Stop'
$project = Split-Path $PSScriptRoot -Parent
if (-not (Test-Path -LiteralPath (Join-Path $project 'compose.yaml'))) {
    throw 'Este instalador es para el Moodle Docker del proyecto original. Para otra instalación sigue README.md.'
}
Push-Location $project
try {
    & docker compose up -d web
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo iniciar Moodle.' }
    & docker compose cp "$PSScriptRoot/theme/aulaverde" web:/var/www/html/public/theme/
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo copiar el tema.' }
    & docker compose cp "$PSScriptRoot/local/plantillacursos" web:/var/www/html/public/local/
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo copiar el generador.' }
    & docker compose exec -T --user www-data web php admin/cli/upgrade.php --non-interactive
    if ($LASTEXITCODE -ne 0) { throw 'Moodle no pudo instalar los complementos.' }
    & docker compose exec -T --user www-data web php public/local/plantillacursos/cli/demo.php --activate-theme
    if ($LASTEXITCODE -ne 0) { throw 'No se pudo crear el curso de ejemplo.' }
} finally { Pop-Location }
