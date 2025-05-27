<template>
  <select v-model="selected" @change="switchLanguage"
          class="border-0 px-2 py-1 pr-7 text-sm dark:bg-gray-800 dark:text-white">
    <option v-for="(label, code) in languages" :key="code" :value="code">
      {{ label }}
    </option>
  </select>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'

const { locale } = useI18n()
const selected = ref(locale.value)

const languages = {
  es: 'Español',
  en: 'English',
  fr: 'Français',
  // Agrega más idiomas según sea necesario
}

function switchLanguage() {
  locale.value = selected.value
  axios.post('/language', { locale: selected.value })
    .then(() => {
      console.log('Idioma cambiado en backend')
    })
    .catch(error => {
      console.error('Error al cambiar idioma en backend:', error)
    })
}
</script>
