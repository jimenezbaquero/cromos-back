<template>
  <div class="bg-white rounded-lg overflow-hidden">


    <!-- Tabla (solo en pantallas md en adelante) -->
    <div class="hidden md:block overflow-x-auto min-h-72">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
          <th
            v-for="(column,index) in columns"
            :key="index"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none"
          >
            <div class="flex items-center px-2 py-1 rounded w-full max-w-sm">
              <div v-if="column.funnel" class="relative">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4 mr-1 cursor-pointer transition duration-150 ease-in-out"
                  :class="{
        'text-gray-400':  !filters[index].funnel?.length,
        'text-blue-600 drop-shadow-md scale-110': filters[index].funnel?.length,
        'text-black': !filters[index].funnel?.length
    }"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  @click="toggleDropdown(index)"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                <!-- Menú desplegable -->
                <div
                  v-if="openFunnel[index]"
                  class="absolute mt-2 w-80 bg-white border border-gray-200 rounded shadow-lg z-10"
                >
                  <div class="flex justify-between px-4 py-2 border-b border-gray-200 text-sm text-gray-700">
                    <button @click.stop="applyFunnelFilter(index)" class="hover:underline">Filtrar</button>
                    <button @click.stop="clearAll(index)" class="hover:underline">Limpiar</button>
                    <button @click.stop="selectAll(index)" class="hover:underline">Seleccionar todos</button>
                  </div>
                  <ul>
                    <li
                      v-for="option in funnels[index]"
                      :key="option.id"
                      class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    >
                      <input
                        type="checkbox"
                        :value="option.id"
                        v-model="option.value"
                        class="mr-2"
                      />
                      {{ option.label }}
                    </li>
                  </ul>
                </div>
              </div>
              <template v-if="column.filterable">
                <label :for="'filter-' + index" class="text-sm text-gray-600">
                  {{ $t(column.label.toLowerCase()) }}
                </label>
                <input
                  :id="'filter-' + index"
                  :type="column.type"
                  v-model="filters[index].value"
                  @input="debouncedSearch"
                  class="px-2 py-1 rounded text-sm border-none w-full"
                />
              </template>
              <template v-else>
                <div class="px-2 py-1 rounded text-sm border-none w-full">
                  {{ $t(column.label.toLowerCase()) }}
                </div>

              </template>
              <template v-if="column.sortable">
                <div @click="sortBy(index)" class="cursor-pointer justify-end w-10 ">
                  <svg v-if="filters[index].sort === 'asc'" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18"/>
                  </svg>

                  <svg v-else-if="filters[index].sort === 'desc'" xmlns="http://www.w3.org/2000/svg" fill="none"
                       viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                  </svg>

                  <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                       stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                  </svg>

                </div>
              </template>
            </div>
          </th>
          <th v-if="rowActions?.length"
              class="px-6 py-3 text-left text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            {{ $t('actions') }}
          </th>
        </tr>
        </thead>
        <tbody v-if="pagination.data.length === 0">
        <tr>
          <td colspan="100%" class="py-10">
            <NoData/>
          </td>
        </tr>
        </tbody>

        <tbody v-else class="bg-white divide-y divide-gray-200">
        <tr v-for="item in pagination.data" :key="item.id" class="hover:bg-gray-200">
          <td
            v-for="(column,index) in columns"
            :key="index"
            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
            @click="goShow(item.id)"
          >
            {{ item[index] }}
          </td>
          <td v-if="rowActions?.length"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 space-x-2 flex justify-around">
            <template v-for="(action, i) in rowActions" :key="i">
              <component
                v-if="action.href"
                :is="action.component || 'a'"
                :href="action.href(item)"
                :class="action.class"
                :title="$t(action.label)"
                class="flex items-center space-x-1"
              >
                <component :is="action.icon" class="w-4 h-4"/>
              </component>
              <button
                v-else
                @click="() => action.onClick(item)"
                :class="action.class"
                :title="$t(action.label)"
                class="flex items-center space-x-1"
              >
                <component :is="action.icon" class="w-4 h-4"/>
              </button>
            </template>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Tarjetas (solo en pantallas pequeñas) -->

    <div class="block md:hidden">
      <!-- Filtro -->
      <div class="py-4 flex w-full">
        <input
          type="text"
          v-model="filters['search'].value"
          @input="debouncedSearch"
          :placeholder="$t('search')+'...'"
          class="border px-4 py-2 rounded w-full text-sm"
        />
      </div>
      <div v-if="!pagination.data.length">
        <NoData/>
      </div>
      <div
        v-else
        v-for="item in pagination.data"
        :key="item.id"
        class="border rounded-lg p-2 shadow-sm mt-2"
      >
        <div class="flex justify-end">
          <template v-for="(action, i) in rowActions" :key="i">
            <button
              @click="() => action.onClick(item)"
              :class="action.class"
              class="flex mr-2 items-center space-x-1"
            >
              <component :is="action.icon" class="w-4 h-4"/>
            </button>
          </template>
        </div>
        <div
          v-for="(column,index) in columns"
          :key="column.key"
          class="mb-1 text-sm"
        >
          <span class="font-semibold text-gray-600 ">{{ $t(column.label) }}:</span>
          <span class="ml-1 text-gray-900">{{ item[index] }}</span>
        </div>
      </div>
    </div>

    <!-- Paginación -->
    <div v-if="pagination.data.length" class="flex justify-end items-center p-4 space-x-2 text-sm">
      <button
          v-for="(link, index) in pagination.links"
          :key="index"
          :disabled="!link.url || link.active"
          @click="changePage(link.label)"
          class="px-3 py-1 rounded border"
          :class="{
    'bg-blue-500 text-white': link.active,
    'text-gray-700 hover:bg-gray-100': !link.active && link.url,
    'text-gray-400 cursor-not-allowed': !link.url,
  }"
          v-html="formatLabel(link.label)"
      />
    </div>
  </div>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {computed, onBeforeMount, onMounted, reactive, ref} from 'vue';
import debounce from 'lodash/debounce';
import NoData from "@/Components/NoData.vue";

const props = defineProps({
  columns: Object,
  filters: Object,
  funnels: Object,
  pagination: Object,
  go_show: {
    type: Boolean,
    default: true
  },
  rowActions: {
    type: Array,
    default: () => [],
  },
  search: {
    type: String,
    default: '',
  },
});

const openFunnel = ref({})

if(props.funnels) {
  Object.keys(props.funnels).forEach((key) => {
    openFunnel.value[key] = false
  })
}

const filteredData = computed(() => {
  return pagination.data.filter(item => {
    return columns.value.every(column => {
      const filterValue = column.filter.value.toLowerCase();
      const itemValue = String(resolveField(item, column.key)).toLowerCase();
      return itemValue.includes(filterValue);
    });
  });
});

const emits = defineEmits(['goShow', 'changeFilters'])

const search = ref(props.search || '');

function toggleDropdown(index) {
  openFunnel.value[index] = !openFunnel.value[index];
}

function formatLabel(label) {
  if (label === 'pagination.previous') return '&laquo;';
  if (label === 'pagination.next') return '&raquo;';
  return label;
}

function sortBy(index) {
  console.log(index)
  let direction = props.filters[index].sort
  Object.keys(props.filters).forEach((key) => {
    if (key !== index && key !== 'search' && key !== 'page') {
      props.filters[key].sort = ''
    }
  })
  props.filters[index].sort = direction === 'asc' ? 'desc' : direction === 'desc' ? '' : 'asc';
  applyFilters();
}

const debouncedSearch = debounce(() => {
  props.filters['page'].value = 1
  applyFilters();
}, 500);

function applyFilters() {
  emits('changeFilters', props.filters)
}

const goShow = (id) => {
  if (props.go_show) {
    emits('goShow', id)
  }
}

function applyFunnelFilter(index) {
  props.filters['page'].value = 1
  props.filters[index].funnel = props.funnels[index].filter(item => item.value === true);
  props.filters[index].value = '';
  props.filters[index].sort = '';
  openFunnel.value[index] = false;
  applyFilters();
}

function clearAll(index) {
  props.funnels[index].forEach(item => item.value = false);
}

function selectAll(index) {
  props.funnels[index].forEach(item => item.value = true);
}

function changePage(page){
  console.log(page)
  if(page.includes('previous')){
    props.filters['page'].value = parseInt(props.filters['page'].value) - 1
  }else if (page.includes('next')) {
    props.filters['page'].value = parseInt(props.filters['page'].value) + 1
  }else {
    props.filters['page'].value = page
  }
  applyFilters()
}

</script>
