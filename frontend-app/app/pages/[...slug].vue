<template>
	<div class="not-found-page">
		<div class="not-found-card">
			<div class="code-badge">404</div>
			<h1>Halaman Tidak Ditemukan</h1>
			<p>Halaman atau berkas yang Anda cari (<code>{{ route.path }}</code>) tidak tersedia atau telah dipindahkan.</p>
			<div class="actions">
				<NuxtLink to="/" class="btn-home">Kembali ke Beranda</NuxtLink>
				<button type="button" @click="router.back()" class="btn-back">Kembali ke Sebelumnya</button>
			</div>
		</div>
	</div>
</template>

<script setup>
const route = useRoute()
const router = useRouter()

// Set status code 404 if running SSR
if (process.server) {
	const event = useRequestEvent()
	if (event) {
		setResponseStatus(event, 404)
	}
}
</script>

<style scoped>
.not-found-page {
	min-height: 100vh;
	display: flex;
	align-items: center;
	justify-content: center;
	background: #090d14;
	color: #e2e8f0;
	padding: 24px;
	font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}
.not-found-card {
	max-width: 480px;
	width: 100%;
	text-align: center;
	padding: 40px 32px;
	border-radius: 16px;
	background: #0f1724;
	border: 1px solid rgba(255, 255, 255, 0.08);
	box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
}
.code-badge {
	display: inline-block;
	font-size: 48px;
	font-weight: 900;
	letter-spacing: -2px;
	background: linear-gradient(135deg, #10b981 0%, #059669 100%);
	-webkit-background-clip: text;
	-webkit-text-fill-color: transparent;
	margin-bottom: 12px;
}
h1 {
	font-size: 20px;
	font-weight: 700;
	color: #ffffff;
	margin: 0 0 12px;
}
p {
	font-size: 13px;
	color: #94a3b8;
	line-height: 1.6;
	margin: 0 0 28px;
}
code {
	padding: 2px 6px;
	border-radius: 4px;
	background: rgba(255, 255, 255, 0.06);
	color: #fbbf24;
	font-family: monospace;
	font-size: 12px;
}
.actions {
	display: flex;
	gap: 12px;
	justify-content: center;
	flex-wrap: wrap;
}
.btn-home {
	padding: 10px 20px;
	border-radius: 8px;
	background: linear-gradient(135deg, #059669 0%, #10b981 100%);
	color: #ffffff;
	font-size: 12px;
	font-weight: 600;
	text-decoration: none;
	transition: all 0.2s ease;
}
.btn-home:hover {
	opacity: 0.9;
	transform: translateY(-1px);
}
.btn-back {
	padding: 10px 18px;
	border-radius: 8px;
	background: #1e293b;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #cbd5e1;
	font-size: 12px;
	cursor: pointer;
	transition: all 0.2s ease;
}
.btn-back:hover {
	background: #334155;
	color: #ffffff;
}
</style>
