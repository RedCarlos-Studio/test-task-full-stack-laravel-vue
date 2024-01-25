<template>
  <div class="flex items-start">
    <div
      class="ml-3 block text-gray-900 w-full"
    >
      <div class="flex items-start justify-between w-full mb-4">
        <h2 :class="`text-lg font-medium py-[6px] ${todo.is_completed ? 'text-indigo-500' : ''} ${todo.is_deleted ? 'line-through text-gray-500' : ''}`">
          {{ todo.title }} {{ todo.is_completed ? '(Task Completed)' : '' }}
        </h2>
        <div class="flex items-center ml-2">
          <button
            v-if="!todo.is_completed && !todo.is_deleted"
            class="max-h-[36px] flex space-x-2 items-center gap-2 px-3 py-2 bg-indigo-500 hover:bg-indigo-800 rounded-md drop-shadow-md text-white"
            @click="() => completeTask(todo.id)"
          >
            <span v-html="iconsList.checkMark" />
            <span class="hidden sm:inline">Complete</span>
          </button>
          <button
            v-if="todo.is_completed && !todo.is_deleted"
            class="max-h-[36px] flex space-x-2 items-center gap-2 px-3 py-2 bg-indigo-500 hover:bg-indigo-800 rounded-md drop-shadow-md text-white"
            @click="() => openTask(todo.id)"
          >
            <span v-html="iconsList.checkMark" />
            <span class="hidden sm:inline">Open</span>
          </button>
          <button
            v-if="!todo.is_deleted && !todo.is_completed"
            class="max-h-[36px] ml-2 flex space-x-2 items-center gap-2 px-3 py-2 bg-indigo-500 hover:bg-indigo-800 rounded-md drop-shadow-md text-white"
            @click="() => showEditModal(todo.id, todo.title, todo.description)"
          >
            <span v-html="iconsList.editIcon" />
            <span class="hidden sm:inline">Edit</span>
          </button>
          <button
            v-if="!todo.is_deleted"
            class="max-h-[36px] ml-2 flex space-x-2 items-center gap-2 px-3 py-2 bg-rose-500 hover:bg-rose-800 rounded-md drop-shadow-md text-white"
            @click="() => openDeleteModal(todo.id)"
          >
            <span v-html="iconsList.removeIcon" />
            <span class="hidden sm:inline">Delete</span>
          </button>
        </div>
      </div>
      <p :class="`text-sm font-light text-gray-500 ${todo.is_completed ? 'text-indigo-500' : ''} ${todo.is_deleted ? 'line-through text-gray-500' : ''}`">
        {{ todo.description }}
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue'

import { TodoDataI } from '@/models/todo.model'
import { ICONS } from '@/statics/icons.static'

export default defineComponent({
  name: 'TodoItem',
  props: {
    todo: {
      type: Object as PropType<TodoDataI>,
      required: true,
    },
  },
  emits: ['showEditModal', 'openDeleteModal', 'completeTask', 'openTask'],

  setup(props, { emit }) {
    const iconsList = ICONS

    const showEditModal = (id: number, title: string, description: string) => {
      emit('showEditModal', { id, title, description })
    }

    const openDeleteModal = (id: number) => {
      emit('openDeleteModal', id)
    }

    const completeTask = (id: number) => {
      emit('completeTask', id)
    }

    const openTask = (id: number) => {
      emit('openTask', id)
    }

    return {
      iconsList,
      showEditModal,
      openDeleteModal,
      completeTask,
      openTask
    }
  },
})
</script>
