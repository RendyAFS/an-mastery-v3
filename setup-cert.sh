#!/bin/bash
# =============================================================================
# setup-cert.sh — Salin mkcert rootCA.pem ke storage project
# Jalankan dari root direktori project Laravel:
#   bash setup-cert.sh
# =============================================================================

set -e

CAROOT=$(mkcert -CAROOT 2>/dev/null)

if [ -z "$CAROOT" ]; then
    echo "ERROR: mkcert tidak ditemukan atau belum di-install."
    echo "Install dengan: sudo apt install mkcert && mkcert -install"
    exit 1
fi

SOURCE="$CAROOT/rootCA.pem"

if [ ! -f "$SOURCE" ]; then
    echo "ERROR: File $SOURCE tidak ditemukan."
    echo "Pastikan mkcert sudah dijalankan dengan: mkcert -install"
    exit 1
fi

DEST="storage/app/cert/rootCA.pem"

mkdir -p storage/app/cert
cp "$SOURCE" "$DEST"

echo ""
echo "✅ Berhasil menyalin:"
echo "   Dari : $SOURCE"
echo "   Ke   : $(pwd)/$DEST"
echo ""
echo "Device lain kini bisa mengunduh cert melalui:"
echo "   https://<ip-server>/cert/download"
echo ""
