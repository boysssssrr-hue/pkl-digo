const fs = require('fs');
const path = require('path');
const QRCode = require('qrcode');

const outputDir = path.join(__dirname, 'public', 'images', 'barcodes');
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

// Generate for RNG-01 through RNG-50ra
const codes = [];
for (let i = 1; i <= 50; i++) {
  const num2 = String(i).padStart(2, '0');
  const num3 = String(i).padStart(3, '0');
  codes.push(`RNG-${num2}`);
  codes.push(`RNG-${num3}`);
  codes.push(`ROOM-${num2}`);
  codes.push(`ROOM-${num3}`);
}

async function run() {
  console.log(`Generating QR code SVGs in ${outputDir}...`);
  let count = 0;
  for (const code of codes) {
    const filename = `qr-${code}.svg`;
    const filePath = path.join(outputDir, filename);
    const svgString = await QRCode.toString(code, {
      type: 'svg',
      width: 200,
      margin: 1,
      color: {
        dark: '#0f172a',
        light: '#ffffff'
      }
    });
    fs.writeFileSync(filePath, svgString, 'utf8');
    count++;
  }
  console.log(`Successfully generated ${count} QR code SVGs for rooms.`);
}

run().catch(console.error);
