<template>
  <div
    class="realtime-clock-container inline-flex items-center gap-2.5 px-3 sm:px-3.5 py-1.5 sm:py-2 rounded-xl border transition-all duration-200 select-none shadow-sm"
    :class="containerClass"
    :title="`Waktu Real-time Sistem: ${fullDateString} • ${timeString} WIB`"
  >
    <!-- Live Pulse Dot Indicator -->
    <span class="relative flex h-2 w-2 shrink-0">
      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
    </span>

    <!-- Date & Time Text -->
    <div class="flex items-center gap-2 text-xs font-semibold leading-normal">
      <!-- Calendar Icon -->
      <span class="text-emerald-500 shrink-0 text-xs">📅</span>

      <!-- Date Portion (Indonesian Day & Date) -->
      <span class="date-text truncate hidden sm:inline" :class="textPrimaryClass">
        {{ dateString }}
      </span>
      <span class="date-text truncate sm:hidden" :class="textPrimaryClass">
        {{ shortDateString }}
      </span>

      <!-- Separator Dot -->
      <span class="opacity-40 text-xs">•</span>

      <!-- Time Portion (Live Ticking with Seconds) -->
      <span class="time-text font-mono font-bold tracking-tight shrink-0" :class="timeTextClass">
        {{ timeString }}
      </span>

      <!-- Timezone Badge -->
      <span class="timezone-badge px-1.5 py-0.5 rounded text-[10px] font-mono font-bold tracking-wider shrink-0" :class="tzBadgeClass">
        WIB
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'default' // 'default' | 'compact' | 'hero' | 'admin'
  },
  theme: {
    type: String,
    default: 'auto' // 'auto' | 'dark' | 'light'
  }
})

// State Waktu Real-Time
const now = ref(new Date())
let timerId = null

const updateClock = () => {
  now.value = new Date()
}

onMounted(() => {
  updateClock()
  timerId = setInterval(updateClock, 1000)
})

onUnmounted(() => {
  if (timerId) clearInterval(timerId)
})

// Format Tanggal Lengkap Bahasa Indonesia: "Jumat, 2 Oktober 2026"
const fullDateString = computed(() => {
  return now.value.toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
})

// Format Tanggal Standar: "Jumat, 2 Okt 2026"
const dateString = computed(() => {
  return now.value.toLocaleDateString('id-ID', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
})

// Format Tanggal Ringkas untuk Mobile: "2 Okt 2026"
const shortDateString = computed(() => {
  return now.value.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short'
  })
})

// Format Jam Menit Detik: "09:07:35"
const timeString = computed(() => {
  const h = String(now.value.getHours()).padStart(2, '0')
  const m = String(now.value.getMinutes()).padStart(2, '0')
  const s = String(now.value.getSeconds()).padStart(2, '0')
  return `${h}:${m}:${s}`
})

// Dynamic Styling Classes
const containerClass = computed(() => {
  if (props.variant === 'hero') {
    return 'bg-slate-900/80 backdrop-blur-md border-emerald-500/40 text-slate-100 shadow-emerald-500/10'
  }
  if (props.variant === 'admin') {
    return 'bg-slate-950/60 hover:bg-slate-950/80 border-slate-800 text-slate-200'
  }
  if (props.theme === 'dark') {
    return 'bg-slate-900/90 border-slate-800 text-slate-100'
  }
  if (props.theme === 'light') {
    return 'bg-slate-100/90 border-slate-200 text-slate-800'
  }
  // Auto mode / default
  return 'bg-slate-900/60 dark:bg-slate-900/90 border-slate-700/50 dark:border-slate-800 text-slate-200'
})

const textPrimaryClass = computed(() => {
  return 'text-slate-200 font-medium'
})

const timeTextClass = computed(() => {
  return 'text-emerald-400 font-black'
})

const tzBadgeClass = computed(() => {
  return 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30'
})
</script>

<style scoped>
.realtime-clock-container {
  white-space: nowrap;
}
</style>
