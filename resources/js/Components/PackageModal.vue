<template>
  <div
    v-if="show"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
  >
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h2 class="text-lg font-semibold mb-4">{{ computedTitle }}</h2>
      <template v-if="showImage">
        <img :src="image" alt="imagen de los cromos del sobre">
      </template>
      <div class="flex justify-end space-x-2">
        <button v-show="!showImage" @click="openPackage" class="px-4 py-2 rounded bg-green-500 text-white hover:bg-green-600">
          {{ t('show') }}
        </button>
        <button v-show="showImage" @click="close" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600">
          {{ t('close') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {computed, ref} from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  show: Boolean,
  title: String,
  image: String
})

console.log(props.image)

const emits = defineEmits(['close'])

const showImage = ref(false)

const computedTitle = computed(() => props.title || t('confirm_action'))

const openPackage = () => {
  showImage.value = true
}

const close = () => {
  showImage.value = false
  return emits('close')
}

</script>
