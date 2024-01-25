<template>
  <div className="fixed top-7 right-0 z-[99] max-w-[767px] min-w-[290px] px-7">
    <div
      className="flex items-center justify-between p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50"
      role="alert"
    >
      <span className="font-medium mr-2">Error!</span>
      {{ errorMessage }}

      <button
        type="button"
        class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
        @click="closeModal"
      >
        <span v-html="iconsList.closeIcon" />
      </button>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'

import { ICONS } from '@/statics/icons.static'

export default defineComponent({
  name: 'NotificationComponent',
  props: {
    message: {
      type: String,
      required: true
    }
  },
  emits: ['closeNotify'],

  setup(props, { emit }) {
    const iconsList = ICONS
    const errorMessage = ref(props.message)

    const closeModal = () => {
      emit('closeNotify')
    }

    return {
      iconsList,
      errorMessage,
      closeModal
    }
  }
})
</script>

<style scoped>

</style>
