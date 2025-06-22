<template>
  <div
    v-if="show"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
  >
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
      <h2 class="text-lg font-semibold mb-4">{{ computedTitle }}</h2>
      <div>
        <label v-for="(product,index) in products"   :key="product.id"
               class="flex items-center space-x-2">
          <input type="radio" :name="groupName" v-model="selectedProduct" :value="product.id"/>
          <span>{{product.name}}</span>
        </label>
      </div>
      <div class="flex justify-end space-x-2">
        <button @click="cancelBuy" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
          {{ t('cancel') }}
        </button>
        <button @click="confirmBuy" class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600">
          {{ t('buy') }}
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
  products: Object
})

const emits = defineEmits(['cancelBuy','confirmBuy'])

const selectedProduct = ref(null)

const computedTitle = computed(() => props.title || t('confirm_action'))

const cancelBuy = () => {
  return emits('cancelBuy')
}

const confirmBuy = () => {
  return emits('confirmBuy', selectedProduct.value)
}

</script>
