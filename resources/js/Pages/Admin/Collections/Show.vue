<template>
  <Head :title="$t('collection_show')"/>
  <AdminLayout>
    <h2 class="text-xl">
      {{ $t('collection_details') }}
    </h2>
    
    <div class="flex justify-end">
      <Link
        :href="route('admin.cards.showCardsCollection',collection.id)"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded"
      >
        {{ $t('show_cards') }}
      </Link>
    </div>
    
    <div class="space-y-2 text-sm">
      <p><strong>{{ $t('name') }}:</strong> {{ collection.name }}</p>
      <p><strong>{{ $t('description') }}:</strong> {{ collection.description }}</p>
      <p><strong>{{ $t('year') }}:</strong> {{ collection.year }}</p>
      <p><strong>{{ $t('publisher') }}:</strong> {{ collection.publisher }}</p>
      <p><strong>{{ $t('cards_number') }}:</strong> {{ collection.card_number }}</p>
    </div>
    
    <div class="w-80 m-auto">
      <h1>Álbum</h1>
      <AlbumSlider :pages="pages" :collection="collection" />
    </div>
    
  </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {computed} from "vue";
import {useI18n} from "vue-i18n";
import {Link} from "@inertiajs/vue3";
import AlbumSlider from "@/Components/AlbumSlider.vue";

const { t } = useI18n()

const props = defineProps({
  collection: Object,
  pages: Object
});

const columns = computed(() => [
  { key: 'id', label: 'ID', sortable: true },
  { key: 'number', label: t('number'), sortable: true },
  { key: 'type_card_id', label: t('type'), sortable: true },
  { key: 'url_photo', label: t('url'), sortable: false },
  { key: 'created_at', label: t('created_at'), sortable: true }
])

</script>
