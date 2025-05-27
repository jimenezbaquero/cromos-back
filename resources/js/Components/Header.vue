<template>
  <header class="flex justify-end items-center gap-4 px-6 py-4 bg-white dark:bg-black">
    <!-- Language Selector -->
    <LanguageSelect/>

    <!-- Login / Register Links -->
    <nav class="flex items-center gap-4 text-sm">
      <Link v-show="canLogin"
            :href="route('login')"
            class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300"
      >
        {{ $t('login') }}
      </Link>

      <Link v-show="canRegister"
            :href="route('register')"
            class="text-black hover:text-gray-700 dark:text-white dark:hover:text-gray-300"
      >
        {{ $t('register') }}
      </Link>
    </nav>
  </header>
</template>

<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {ref} from 'vue'
import {useI18n} from 'vue-i18n'
import LanguageSelect from "@/Components/LanguageSelect.vue";

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
})

const {locale} = useI18n()
const currentLocale = ref(locale.value)

const languages = {
  es: 'Español',
  en: 'English',
  fr: 'Français',
}

function changeLocale() {
  const lang = currentLocale.value

  axios.post('/language', {locale: lang})
      .then(() => {
        locale.value = lang
        console.log('Idioma cambiado en backend');
      })
      .catch(error => {
        console.error('Error al cambiar idioma en backend:', error)
      })
}
</script>
