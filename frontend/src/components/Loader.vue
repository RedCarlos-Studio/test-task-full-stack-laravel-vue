<template>
  <div class="fixed inset-0 z-60 flex items-center justify-center w-full h-full bg-white/90">
    <span v-html="iconsList.loaderIcon" />
    <span class="sr-only">Loading...</span>
  </div>
</template>

<script>
import { ICONS } from '@/statics/icons.static'

export default {
  name: 'LoaderComponent',

  setup() {
    const iconsList = ICONS

    return {
      iconsList
    }
  }
}
</script>
