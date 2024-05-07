<script setup lang="ts">
import type { Task } from '@/types'
import { onMounted, ref } from 'vue'
import { Api } from '@/api/api'

import { TrashIcon, CloudArrowDownIcon, BackspaceIcon } from '@heroicons/vue/24/solid'
import { toast } from 'vue3-toastify'

const api = new Api()
const loading = ref(false)
const uploading = ref(false)
const task = ref<Task | null>(null)
const props = defineProps<{
  id: string
}>()

const deleteAttachment = async (id: string) => {
  await api.deleteAttachment(id)
    .then(() => {
      console.log('Deleted')
    })
    .catch(() => {
      console.log('Error deleting')
    })
}

const downloadAttachment = async (id: string, name: string) => {
  await api.downloadAttachment(id, name)
    .then(() => {
      console.log('Downloaded')
    })
    .catch(() => {
      console.log('Error downloading')
    })
}

const uploadAttachment = async () => {
  uploading.value = true
  const fileInput = document.getElementById('upload-file') as HTMLInputElement
  fileInput.click()
  fileInput.onchange = async () => {
    const file = fileInput.files?.[0]
    if (file) {
      await api.uploadAttachment(props.id, file)
        .then(async () => {
          uploading.value = false
          //Refresh task details
          await api.getTask(props.id)
            .then(({ data }) => {
              task.value = data
            })
        })
        .catch(() => {
          console.log('Error uploading')
        })
    }
  }
}

const onSubmit = async (event: Event) => {
  event.preventDefault()
  const title = (document.getElementById('title') as HTMLInputElement).value
  const description = (document.getElementById('description') as HTMLInputElement).value
  const due_date = (document.getElementById('due_date') as HTMLInputElement).value

  await api.updateTask(props.id, { title, description, due_at: due_date })
    .then(() => {
     toast('Task updated', { type: 'success', delay: 100, autoClose: true })
    })
    .catch(() => {
      console.log('Error updating')
    })
}


onMounted(async () => {
  // Fetch task details
  loading.value = true
  await api.getTask(props.id)
    .then(({ data }) => {
      task.value = data
      loading.value = false
    })
})


</script>

<template>
  <div class="max-w-[700px]  mx-auto my-0 px-[15px] py-0  pt-4">

    <div class="bg-[#fff] rounded-md p-[30px] shadow flex-col" v-if="!loading">
      <button @click="$router.back()" class="bg-emerald-500 hover:bg-emerald-700 text-white py-2 px-4 rounded mb-3">
       Regresar
      </button>
      <form @submit="onSubmit" class="flex flex-col gap-3">
        <input type="text" id="title" class="w-full p-2 border border-gray-300 rounded mb-3" :value="task?.title">
        <textarea id="description" class="w-full p-2 border border-gray-300 rounded mb-3" :value="task?.description" />
        <input type="text" id="due_date" class="w-full p-2 border border-gray-300 rounded mb-3" :value="task?.due_at">

        <button class="bg-emerald-500 hover:bg-emerald-700 text-white py-2 px-4 rounded mb-3">
          Actualizar tarea
        </button>
      </form>

    </div>

    <div v-if="loading" class="bg-[#fff] rounded-md p-[30px] shadow flex justify-center">
      <p class="text-2xl text-gray-900 dark:text-white">Cargando...</p>
    </div>

  </div>
</template>

<style scoped>

</style>