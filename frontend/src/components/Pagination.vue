<template>
  <div
    class="mt-3 flex justify-center items-center max-w-full"
  >
    <button
      :disabled="!links.prev"
      :class="`m-1 px-2 py-1 border rounded bg-gray-200 ${!links.prev ? 'pointer-events-none cursor-not-allowed opacity-50' : ''}`"
      @click="fetchPage(links.prev)"
    >
      Previous
    </button>
    <div
      ref="paginationContainer"
      class="flex items-center max-w-full overflow-auto no-scrollbar"
    >
      <button
        v-for="page in getPaginationRange()"
        :key="page"
        class="m-1 px-2 py-1 border rounded bg-gray-200"
        :class="{ 'bg-blue-500 text-white': page === meta.current_page }"
        @click="fetchPage(`${apiUrl}/tasks?page=${page}`)"
      >
        {{ page }}
      </button>
    </div>
    <button
      :disabled="!links.next"
      :class="`m-1 px-2 py-1 border rounded bg-gray-200 ${!links.next ? 'pointer-events-none cursor-not-allowed opacity-50' : ''}`"
      @click="fetchPage(links.next)"
    >
      Next
    </button>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch, PropType } from 'vue'

import { TodoLinksI, TodoMetaI } from '@/models/todo.model'
import { API_URL } from '@/config/config'

const STEP = 100
const INITIAL_PAGINATION_PAGE = 1
const PAGINATION_PAGE_STEP = 2

export default defineComponent({
  name: 'PaginationComponent',
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    linksData: {
      type: Object as PropType<TodoLinksI>,
      required: true,
    },
    metaData: {
      type: Object as PropType<TodoMetaI>,
      required: true,
    },
  },
  emits: ['getTaskList', 'setCurrentPage'],

  setup(props, { emit }) {
    const apiUrl = API_URL
    const links = ref<TodoLinksI>(props.linksData)
    const meta = ref<TodoMetaI>(props.metaData)
    const originalPage = ref(props.currentPage)

    watch(originalPage, (newPage) => {
      emit('getTaskList', newPage)
      emit('setCurrentPage', newPage)
    })

    watch([() => props.linksData, () => props.metaData], ([newLinksData, newMetaData]) => {
      links.value = newLinksData
      meta.value = newMetaData
    })

    const fetchPage = (url: string) => {
      const page = new URL(url).searchParams.get('page')
      if (page) {
        originalPage.value = +page
      }
    }

    const getPaginationRange = () => {
      const total_pages = Number(meta.value.last_page)
      const current_page = Number(meta.value.current_page)

      const range = []
      for (let i = INITIAL_PAGINATION_PAGE; i <= total_pages; i++) {
        if (i === INITIAL_PAGINATION_PAGE || i === total_pages || (i >= current_page - PAGINATION_PAGE_STEP && i <= current_page + PAGINATION_PAGE_STEP)) {
          range.push(i)
        } else if (range[range.length - INITIAL_PAGINATION_PAGE] !== '...') {
          range.push('...')
        }
      }

      return range
    }

    return {
      apiUrl,
      links,
      meta,
      originalPage,
      fetchPage,
      getPaginationRange
    }
  },

  watch: {
    'meta.current_page'(newPage, oldPage) {
      const direction = newPage > oldPage ? 'next' : 'prev'
      this.scrollContainer(direction)
    }
  },

  methods: {
    scrollContainer(direction: 'prev' | 'next') {
      const container = this.$refs.paginationContainer as HTMLElement

      if (container) {
        const scrollAmount = direction === 'prev' ? -STEP : STEP
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' })
      }
    }
  }
})
</script>

<style scoped>

</style>
