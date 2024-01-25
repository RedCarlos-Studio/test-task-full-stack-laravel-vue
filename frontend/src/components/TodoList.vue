<template>
  <template v-if="loading">
    <LoaderComponent />
  </template>

  <template v-if="!loading">
    <div class="max-w-[960px] min-h-[418px] w-full mx-auto bg-white shadow-lg rounded-lg">
      <div class="px-4 py-2">
        <h1 class="text-gray-800 font-bold text-2xl uppercase">
          To-Do List
        </h1>
      </div>
      <form class="w-full mx-auto px-4 py-2">
        <div class="flex items-center justify-end border-b-2 border-teal-500 py-2">
          <button
            class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
            type="button"
            @click="showAddModal"
          >
            Add
          </button>
        </div>
      </form>
      <div
        v-if="allTodos.length"
        class="divide-y divide-gray-200 px-4 max-h-[300px] overflow-auto"
      >
        <div
          v-for="(todo) in allTodos"
          :key="todo.id"
          class="py-4"
        >
          <TodoItem
            :todo="todo"
            @showEditModal="showEditModal"
            @openDeleteModal="openDeleteModal"
            @completeTask="handleCompleteTask"
            @openTask="handleOpenTask"
          />
        </div>
      </div>
      <div
        v-else
        class="p-4 w-full flex items-center justify-center"
      >
        <h2>No Todos Yet! 📝✨</h2>
      </div>
    </div>

    <ModalComponent
      v-if="modalData.isModalVisible"
      :modalData="modalData"
      @submit="handleSubmit"
      @close="closeModal"
    />

    <ModalConfirm
      :id="todoId"
      :isVisible="isConfirmModalVisible"
      @close="closeDeleteModal"
      @confirm="confirmDelete"
    />

    <template v-if="allTodos.length">
      <PaginationComponent
        :currentPage="currentPage"
        :linksData="linksData"
        :metaData="metaData"
        @setCurrentPage="setCurrentPage"
        @getTaskList="getTaskList"
      />
    </template>
  </template>

  <NotificationComponent
    v-if="notifyData.isOpen"
    :message="notifyData.message"
    @closeNotify="hideNotify"
  />
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'

import LoaderComponent from '@/components/Loader.vue'
import TodoItem from '@/components/Todo.vue'
import ModalComponent from '@/components/Modals/Modal.vue'
import ModalConfirm from '@/components/Modals/ModalConfirm.vue'
import PaginationComponent from '@/components/Pagination.vue'
import NotificationComponent from '@/components/Notofication/Notification.vue'
import { ModalI, ModalTypesE } from '@/models/modals.model'
import { getTasks } from '@/http/getTasks'
import { FormT } from '@/models/form.model'
import { createTask } from '@/http/createTask'
import { editTask } from '@/http/editTask'
import { deleteTask } from '@/http/deleteTask'
import { completeTask } from '@/http/completeTask'
import { openTask } from '@/http/openTask'
import { TodoDataI, TodoI, TodoLinksI, TodoMetaI } from '@/models/todo.model'
import {
  INITIAL_NOTIFY,
  INITIAL_TODO_LIST_LINKS,
  INITIAL_TODO_LIST_META,
  INITIAL_TODO_LIST_PAGE
} from '@/statics/todo.static'
import { setError } from '@/utils/setError'
import { ApiResponse } from '@/models/http.model'

export default defineComponent({
  name: 'TodoList',
  components: {
    LoaderComponent,
    TodoItem,
    ModalComponent,
    ModalConfirm,
    PaginationComponent,
    NotificationComponent
  },

  setup() {
    const allTodos = ref<TodoDataI[]>([])
    const loading = ref(true)
    const isConfirmModalVisible = ref(false)
    const todoId = ref<number | null>(null)
    const modalData = ref<ModalI>({ isModalVisible: false, type: null, id: null, title: '', description: '' })
    const linksData = ref<TodoLinksI>(INITIAL_TODO_LIST_LINKS)
    const metaData = ref<TodoMetaI>(INITIAL_TODO_LIST_META)
    const currentPage = ref(INITIAL_TODO_LIST_PAGE)
    const notifyData = ref(INITIAL_NOTIFY)

    const getTaskList = async (newPage?: number) => {
      try {
        await getTasks(newPage).then((res: TodoI) => {
          const { data, links, meta } = res.data
          allTodos.value = data || []
          linksData.value = links || INITIAL_TODO_LIST_LINKS
          metaData.value = meta || INITIAL_TODO_LIST_META
          loading.value = false
        })
      } catch (err) {
        notifyData.value = {
          isOpen: true,
          message: setError(err as ApiResponse)
        }

        loading.value = false
      }
    }

    const onCreateTask = async (title: string, description: string) => {
      try {
        await createTask(title, description).then(() => {
          closeModal()
          currentPage.value = INITIAL_TODO_LIST_PAGE
          getTaskList(INITIAL_TODO_LIST_PAGE)
        })
      } catch (err) {
        notifyData.value = {
          isOpen: true,
          message: setError(err as ApiResponse)
        }

        loading.value = false
      }
    }

    const onEditTask = async (id: number, title: string, description: string) => {
      try {
        await editTask(id, title, description).then(() => {
          closeModal()
          getTaskList(currentPage.value)
        })
      } catch (err) {
        notifyData.value = {
          isOpen: true,
          message: setError(err as ApiResponse)
        }

        loading.value = false
      }
    }

    const onDeleteTask = async (id: number) => {
      try {
        await deleteTask(id).then(() => {
          closeDeleteModal()
          getTaskList(currentPage.value)
        })
      } catch (err) {
        notifyData.value = {
          isOpen: true,
          message: setError(err as ApiResponse)
        }

        loading.value = false
      }
    }

    const onCompleteTask = async (id: number) => {
      try {
        await completeTask(id).then(() => {
          closeDeleteModal()
          getTaskList(currentPage.value)
        })
      } catch (err) {
        notifyData.value = {
          isOpen: true,
          message: setError(err as ApiResponse)
        }

        loading.value = false
      }
    }

    const onOpenTask = async (id: number) => {
      try {
        await openTask(id).then(() => {
          closeDeleteModal()
          getTaskList(currentPage.value)
        })
      } catch (err) {
        notifyData.value = {
          isOpen: true,
          message: setError(err as ApiResponse)
        }

        loading.value = false
      }
    }

    getTaskList()

    const setCurrentPage = (page: number) => {
      currentPage.value = page
    }

    const showAddModal = () => {
      modalData.value = { isModalVisible: true, type: ModalTypesE.Create, id: null, title: '', description: '' }
    }

    const showEditModal = ({ id, title, description }: { id: number, title: string; description: string }) => {
      modalData.value = { isModalVisible: true, type: ModalTypesE.Edit, id, title, description }
    }

    const openDeleteModal = (id: number) => {
      isConfirmModalVisible.value = true
      todoId.value = id
    }

    const closeModal = () => {
      modalData.value = { isModalVisible: false, type: null, id: null, title: '', description: '' }
    }

    const closeDeleteModal = () => {
      isConfirmModalVisible.value = false
    }

    const handleSubmit = (form: FormT) => {
      loading.value = true
      if (form.id) {
        onEditTask(form.id, form.title, form.description)

        return
      }

      onCreateTask(form.title, form.description)
    }

    const confirmDelete = (id: number) => {
      loading.value = true
      onDeleteTask(id)
    }

    const handleCompleteTask = (id: number) => {
      onCompleteTask(id)
    }

    const handleOpenTask = (id: number) => {
      onOpenTask(id)
    }

    const hideNotify = () => {
      notifyData.value = INITIAL_NOTIFY
    }

    return {
      loading,
      allTodos,
      isConfirmModalVisible,
      modalData,
      todoId,
      linksData,
      metaData,
      currentPage,
      closeModal,
      handleSubmit,
      closeDeleteModal,
      showAddModal,
      showEditModal,
      openDeleteModal,
      confirmDelete,
      handleCompleteTask,
      handleOpenTask,
      setCurrentPage,
      getTaskList,
      notifyData,
      hideNotify
    }
  },
})
</script>
