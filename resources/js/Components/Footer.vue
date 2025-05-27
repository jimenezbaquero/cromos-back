<template>
  <footer class="py-6 h-2 flex items-center justify-center text-center text-sm text-black dark:text-white/70">
    <div class="footer">
      &copy; {{ year }} {{ appname }}. {{ $t('all_rights') }}
    </div>
  </footer>
</template>

<script setup>

const year = new Date().getFullYear()
const appname = import.meta.env.VITE_APP_NAME || 'MiAplicacion'
</script>