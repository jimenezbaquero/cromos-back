<template>
  <swiper
    :modules="[Navigation, Pagination, EffectFlip]"
    :effect="'flip'"
    :grab-cursor="true"
    :pagination="{ clickable: true }"
    :navigation="true"
    @slideChange="onSlideChange"
  >
    <!-- Portada -->
    <swiper-slide>
      <img :src="collection.cover_url" alt="Portada" />
    </swiper-slide>
    
    <!-- Páginas de cromos -->
    <swiper-slide v-for="(page, index) in pages" :key="index">
<!--      <PageGrid :page-data="page" />-->
      <img class="margin-auto" :src="page" loading="lazy"/>
    </swiper-slide>
    
    <!-- Contraportada -->
    <swiper-slide>
      <img :src="collection.backcover_url" alt="Contraportada" />
    </swiper-slide>
  </swiper>
</template>

<script setup>

import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, EffectFlip } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-flip';

import { ref } from 'vue';
// import PageGrid from './PageGrid.vue';

const props = defineProps({
  pages: Object,
  collection: Object
});

const pages = ref(props.pages)

const onSlideChange = (swiper) => {
  const nextIndex = swiper.activeIndex; // índice actual
  const preloadIndex = nextIndex + 3; // siguiente página a cargar
  
  if (!pages.value[preloadIndex]) {
    console.log('cargando pagina',preloadIndex)
    axios.get(route('admin.collections.getPage', { page: preloadIndex + 1, collection: parseInt(props.collection.id) }))
      .then(res => {
        if(res.data.exists) {
          pages.value[preloadIndex] = res.data.page;
        }
      });
  }
};

</script>