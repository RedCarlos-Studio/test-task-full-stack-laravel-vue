<template>
  <div
    v-show="modalData.isModalVisible"
    class="fixed top-50 left-50 z-50 flex justify-center items-center w-full max-h-full pointer-events-none"
  >
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow pointer-events-auto">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold text-gray-900">
            {{ modalData.type === modalTypes.Create ? 'Add Todo' : 'Edit Todo' }}
          </h3>
          <button
            type="button"
            class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            @click="closeModal"
          >
            <span v-html="iconsList.closeIcon" />
          </button>
        </div>
        <div class="p-5 md:p-6">
          <form
            class="space-y-4"
            novalidate
            @submit.prevent="submitHandler"
          >
            <div>
              <label
                for="title"
                class="block mb-2 text-sm font-medium text-gray-900"
              >Title</label>
              <input
                id="title"
                v-model="form.title"
                :class="`bg-gray-50 border border-gray-300 ${ errors.title ? 'border-red-500' : '' } text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5`"
                type="text"
                placeholder="Enter task name"
                required
                @input="onChange($event, 'title')"
              >
              <p
                v-if="errors.title"
                class="text-red-500"
              >
                {{ errors.title }}
              </p>
            </div>
            <div>
              <label
                for="description"
                class="block mb-2 text-sm font-medium text-gray-900"
              >Description</label>
              <textarea
                id="description"
                v-model="form.description"
                name="description"
                placeholder="Enter task description"
                :class="`bg-gray-50 resize-none border border-gray-300 ${ errors.description ? 'border-red-500' : '' } text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5`"
                required
                @input="onChange($event, 'description')"
              />
              <p
                v-if="errors.description"
                class="text-red-500"
              >
                {{ errors.description }}
              </p>
            </div>
            <button
              type="submit"
              class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
            >
              {{ modalData.type === modalTypes.Create ? 'Create' : 'Edit' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div
    v-show="modalData.isModalVisible"
    class="fixed bg-black/50 z-40 top-0 left-0 right-0 bottom-0 cursor-pointer"
    @click="closeModal"
  />
</template>

<script lang="ts">
import { defineComponent, watch, ref } from 'vue'
import { PropType } from 'vue/dist/vue'

import { FormT } from '@/models/form.model'
import { ModalI, ModalTypesE } from '@/models/modals.model'
import { ICONS } from '@/statics/icons.static'
import { DESCRIPTION_ERROR, maxLengthError, minLengthError, TITLE_ERROR } from '@/statics/todo-list-errors.static'

export default defineComponent({
  name: 'ModalComponent',
  props: {
    modalData: {
      type: Object as PropType<ModalI>,
      required: true,
    }
  },
  emits: ['close', 'submit'],

  setup(props, { emit }) {
    const iconsList = ICONS
    const modalTypes = ModalTypesE
    const errors = ref<{
      title: string,
      description: string,
      [key: string]: string
    }>({
      title: '',
      description: ''
    })
    const form: FormT = {
      id: props.modalData.id || null,
      title: props.modalData.title || '',
      description: props.modalData.description || ''
    }

    const closeModal = () => {
      emit('close')
    }

    const onChange = (e: Event, name: string) => {
      const { value } = e.target as HTMLInputElement
      form[name] = value

      if (value.length < 3) {
        errors.value[name] = minLengthError(name)

        return
      }

      if (name === 'title' && value.length > 255 || name === 'description' && value.length > 750) {
        errors.value[name] = maxLengthError(name)

        return
      }

      errors.value[name] = ''
    }

    const submitHandler = () => {
      if (!form.title) {
        errors.value.title = TITLE_ERROR
      }

      if (!form.description) {
        errors.value.description = DESCRIPTION_ERROR
      }

      if (form.title.length < 3 || form.description.length < 3 || form.title.length > 255 || form.description.length > 750) return

      if (form.title && form.description) {
        emit('submit', form)
      }
    }

    watch(() => props.modalData, (newVal) => {
      form.id = newVal.id || null
      form.title = newVal.title || ''
      form.description = newVal.description || ''
    })

    return {
      errors,
      iconsList,
      modalTypes,
      form,
      onChange,
      submitHandler,
      closeModal,
    }
  },
})
</script>
