<template>
  <div class="min-h-screen bg-slate-950 flex items-center justify-center text-slate-400 text-xs">
    <span>Mengarahkan ke Profil Administrator...</span>
  </div>
</template>

<script setup>
const router = useRouter()
onMounted(() => {
  router.replace('/admin/profil')
})
</script>
