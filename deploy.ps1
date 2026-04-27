$ErrorActionPreference = 'Stop'

$MODULE_NAME = "api_data_builder_sample"
$TARGET_DEPLOY = "D:\Data\Envato deploy\Add-on\PerfexCRM\ApiSample"

Write-Host "Package ${MODULE_NAME}.zip..." -ForegroundColor Cyan

# Create target dir if not exists
if (!(Test-Path -Path $TARGET_DEPLOY)) {
    New-Item -ItemType Directory -Force -Path $TARGET_DEPLOY | Out-Null
}

$CurrentDir = Get-Location
$ParentDir  = Split-Path -Parent $CurrentDir

# Move to parent (modules/) so the zip contains the folder name
Set-Location $ParentDir

$ZipPath = Join-Path $ParentDir "$MODULE_NAME.zip"
if (Test-Path $ZipPath) { Remove-Item $ZipPath -Force }

# ========================================
# PREPARE TEMP COPY (exclude private/dev files)
# ========================================
$TempBasePath = Join-Path $ENV:TEMP "${MODULE_NAME}_deploy"
if (Test-Path $TempBasePath) { Remove-Item -Recurse -Force $TempBasePath }
New-Item -ItemType Directory -Force -Path $TempBasePath | Out-Null
$TempDir = Join-Path $TempBasePath $MODULE_NAME

Write-Host "Preparing files..."

$SourceDir = Join-Path $ParentDir $MODULE_NAME

$RobocopyArgs = @(
    $SourceDir,
    $TempDir,
    "/E",
    "/XD", ".git", "_private_note", "heraspec", "node_modules", ".agents", ".ai",
    "/XF", "AGENTS.heraspec.md", ".gitignore", ".gitattributes", ".DS_Store", "*.ps1", "*.sh", "*.txt",
    "/NJH", "/NJS", "/NDL", "/NC", "/NS"
)
& robocopy $RobocopyArgs | Out-Null

# ========================================
# CREATE ZIP
# ========================================
Write-Host "Creating ${MODULE_NAME}.zip..."
Set-Location -Path $TempBasePath
& tar -a -cf "$ZipPath" "$MODULE_NAME"
Set-Location -Path $CurrentDir
Remove-Item -Recurse -Force $TempBasePath

Write-Host "Done: ${MODULE_NAME}.zip" -ForegroundColor Green

# ========================================
# COPY TO TARGET DEPLOY
# ========================================
Write-Host "Copying to deploy target..."
if (Test-Path $ZipPath) {
    $DestZip = Join-Path $TARGET_DEPLOY "${MODULE_NAME}.zip"
    if (Test-Path $DestZip) { Remove-Item -Force $DestZip }
    Copy-Item -Path $ZipPath -Destination $TARGET_DEPLOY
    Write-Host "Copied ${MODULE_NAME}.zip -> ${TARGET_DEPLOY}" -ForegroundColor Green
} else {
    Write-Host "Error: ZIP file not found!" -ForegroundColor Red
    exit 1
}

Write-Host "`nDeploy complete!" -ForegroundColor Green
