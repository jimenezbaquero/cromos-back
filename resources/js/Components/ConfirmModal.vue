<template>
  <div
    v-if="show"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
  >
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h2 class="text-lg font-semibold mb-4">{{ computedTitle }}</h2>
      <p class="text-sm text-gray-700 mb-6">{{ computedMessage }}</p>
      <div class="flex justify-end space-x-2">
        <button @click="$emit('cancelAction')" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
          {{ t('cancel') }}
        </button>
        <button @click="$emit('confirmAction')" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600">
          {{ t('confirm') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const props = defineProps({
  show: Boolean,
  title: String,
  message: String,
})

const computedTitle = computed(() => props.title || t('confirm_action'))
const computedMessage = computed(() => props.message || t('sure'))
</script>
