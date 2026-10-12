<script setup>
const userCookie = useCookie('auth_user')

onMounted(() => {
	const role = userCookie.value?.role
	if (role === 'admin') {
		navigateTo('/admin/dashboard', { replace: true })
	} else if (role === 'sopir') {
		navigateTo('/sopir/dashboard', { replace: true })
	} else if (role === 'ob') {
		navigateTo('/ob/dashboard', { replace: true })
	} else if (role === 'user') {
		navigateTo('/User/dashboard', { replace: true })
	} else {
		navigateTo('/login', { replace: true })
	}
})
</script>

<template>
	<div class="min-h-screen flex items-center justify-center bg-slate-950 text-slate-400 font-sans">
		<div class="text-center">
			<div class="inline-block w-8 h-8 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mb-3"></div>
			<p class="text-sm">Mengalihkan ke dashboard Anda...</p>
		</div>
	</div>
</template>
