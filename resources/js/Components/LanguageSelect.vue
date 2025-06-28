<!--<template>-->
<!--  <select v-model="selected" @change="switchLanguage"-->
<!--          class="border-0 px-2 py-1 pr-7 text-sm dark:bg-gray-800 dark:text-white">-->
<!--    <option v-for="(label, code) in languages" :key="code" :value="code">-->
<!--      {{ label }}-->
<!--    </option>-->
<!--  </select>-->
<!--</template>-->

<template>
  <Dropdown align="right" width="48">
    <template #trigger>
      <button class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
        {{ languages[selected] }}
        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
    </template>
    <template #content>
      <DropdownOption v-for="(label, code) in languages" :key="code" @click="switchLanguage(code)" >{{label}}</DropdownOption>
    </template>
  </Dropdown>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'
import Dropdown from "@/Components/Dropdown.vue";
import DropdownOption from "@/Components/DropdownOption.vue";

const { locale } = useI18n()
const selected = ref(locale.value)

const languages = {
  es: 'Español',
  en: 'English',
  fr: 'Français',
  // Agrega más idiomas según sea necesario
}

function switchLanguage(code) {
  axios.post('/language', { locale: code })
    .then(() => {
      locale.value = code
      selected.value = code
      console.log('Idioma cambiado en backend')
    })
    .catch(error => {
      console.error('Error al cambiar idioma en backend:', error)
    })
}
</script>
