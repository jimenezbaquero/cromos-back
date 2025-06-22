<template>
  <Head :title="$t('collections')" />

  <div v-if="isLoading" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
    </svg>
  </div>

  <ClientLayout>
    <h2 class="text-xl">
      {{ $t('collections') }}
    </h2>

    <div class="py-6">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-lg pt-0">
          <Datatable
              :columns="headers"
              :pagination="filteredData"
              :row-actions="rowActions"
              :filters="filters"
              :funnels="funnels"
              :go_show="true"
              @goShow = "goShow"
              @changeFilters = "changeFilters"
          />
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de borrado -->
    <ConfirmModal
      :show="showConfirmModal"
      :title="$t('delete_collection')"
      :message="$t('sure_to') + ' ' + selectedCollection?.name + '? ' + $t('cant_undone')"
      @cancelAction="cancelDelete"
      @confirmAction="performDelete"
    />
    
    <!-- Modal de compra -->
    <BuyModal
      :show="showBuyModal"
      :products="products"
      :title="$t('buy_package')"
      @cancelBuy="cancelBuy"
      @confirmBuy="handleBuy"
    />
    
    <PackageModal
      :show="showPackageModal"
      :image="packageImage"
      :title="$t('open_package')"
      @close="closePackageModal"
    />
    
  </ClientLayout>
</template>

<script setup>
import Datatable from '@/Components/Datatable.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import {Head, router} from '@inertiajs/vue3'
import { useFlashFromResponse } from '@/Composables/useFlashFromResponse'
import {PencilSquareIcon, TrashIcon, EyeIcon, WalletIcon} from '@heroicons/vue/24/solid'
import ClientLayout from "@/Layouts/ClientLayout.vue";
import BuyModal from "@/Components/BuyModal.vue";
import PackageModal from "@/Components/PackageModal.vue";

const props = defineProps({
  collections: Object,
  headers: Object,
  filters: Object,
  funnels: Object,
  products: Object
})

const { t } = useI18n()
const { showFlash } = useFlashFromResponse()

const showConfirmModal = ref(false)
const showBuyModal = ref(false)
const showPackageModal = ref(false)
const isLoading = ref(false)
const selectedCollection = ref(null)
const filteredData = ref(props.collections)
const packageImage = ref(null)

const rowActions = [
  {
    label: 'edit',
    icon: PencilSquareIcon,
    onClick: (item) => router.visit(route('admin.collections.edit', item.id)),
    class: 'text-blue-600 hover:text-blue-800'
  },
  {
    label: 'show',
    icon: EyeIcon,
    onClick: (item) => router.visit(route('admin.collections.show', item.id)),
    class: 'text-yellow-600 hover:text-yellow-800'
  },
  {
    label: 'buy',
    icon: WalletIcon,
    onClick: (item) => openBuyModal(item.id),
    class: 'text-green-600 hover:text-green-800'
  },
  {
    label: 'delete',
    icon: TrashIcon,
    onClick: (item) => confirmDelete(item),
    class: 'text-red-600 hover:text-red-800'
  }
]


const openBuyModal = (id) => {
  console.log('abriendo modal de compra')
  selectedCollection.value = id
  showBuyModal.value = true
}

function confirmDelete(collection) {
  selectedCollection.value = collection
  showConfirmModal.value = true
}

function performDelete() {
  isLoading.value = true
  if (selectedCollection.value) {
    router.delete(route('admin.collections.destroy', selectedCollection.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        cancelDelete()
        isLoading.value = false
        showFlash()
      },
      onError: () => {
        isLoading.value = false
        showFlash()
      }
    })
  }
}

function cancelDelete() {
  showConfirmModal.value = false
  selectedCollection.value = null
}

function cancelBuy() {
  showBuyModal.value = false
  selectedCollection.value = null
}

function handleBuy(product) {
  isLoading.value = true
  packageImage.value = null
  axios.post(route('client.collections.buy', {'collection':selectedCollection.value,'product':product}), {
    preserveScroll: true})
    .then(response => {
      isLoading.value = false
      showFlash()
      showBuyModal.value = false
      showPackage(response.data.image)
      })
    .catch(() => {
      showFlash()
    })
}

const showPackage = (image) => {
  packageImage.value = image
  showPackageModal.value = true
}

function goShow(id){
  router.visit(route('admin.collections.show', id))
}

const changeFilters = (filters) => {
  isLoading.value = true
  axios.post(route('admin.collections.getData'),filters)
      .then((response) => {
        filteredData.value = response.data
        isLoading.value = false
      })
}

const closePackageModal = () => {
  console.log('cerrando modal en index')
  showPackageModal.value = false
  packageImage.value = null
  
}

</script>
