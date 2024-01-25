<template>
  <div
    class="w-full h-full flex flex-col justify-center bg-gray-300"
  >
    <div class="w-full h-full flex flex-col justify-center items-center p-4 m-auto">
      <Suspense>
        <TodoList />
      </Suspense>
    </div>
  </div>
</template>

<script>

import TodoList from './components/TodoList.vue'

export default {
  name: 'App',
  components: {
    TodoList,
  }
}
</script>
