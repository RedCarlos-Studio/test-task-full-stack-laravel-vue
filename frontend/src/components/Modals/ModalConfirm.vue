<template>
  <div
    v-show="isVisible"
    class="fixed top-50 left-50 z-50 flex justify-center items-center w-full max-h-full pointer-events-none"
  >
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow pointer-events-auto">
        <button
          type="button"
          class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
          @click="closeModal"
        >
          <span v-html="iconsList.closeIcon" />
        </button>
        <div class="p-4 md:p-5 text-center">
          <span v-html="iconsList.warningIcon" />
          <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
            Are you sure you want to delete this Todo?
          </h3>
          <button
            type="button"
            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2"
            @click="confirmAction"
          >
            Yes
          </button>
          <button
            type="button"
            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
            @click="closeModal"
          >
            No
          </button>
        </div>
      </div>
    </div>
  </div>
  <div
    v-show="isVisible"
    class="fixed bg-black/50 z-40 top-0 left-0 right-0 bottom-0 cursor-pointer"
    @click="closeModal"
  />
</template>

<script>
import { defineComponent } from 'vue'

import { ICONS } from '@/statics/icons.static'

export default defineComponent({
  name: 'ModalConfirm',
  props: {
    isVisible: {
      type: Boolean,
      default: false,
    },
    id: {
      type: Number,
      require: true,
      default: null,
    },
  },
  emits: ['close', 'confirm'],

  setup(props, { emit }) {
    const iconsList = ICONS
    const closeModal = () => {
      emit('close')
    }

    const confirmAction = () => {
      if (!props.id) return

      emit('confirm', props.id)
    }

    return {
      iconsList,
      closeModal,
      confirmAction,
    }
  },
})
</script>
