<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import type { Task } from '@/types'

import { Api } from '@/api/api'
import { formatearFecha } from '@/helper'
import { toast } from 'vue3-toastify'
import TaskFooter from '@/components/TaskFooter.vue'
import TaskLoading from '@/components/TaskLoading.vue'
import TaskEmpty from '@/components/TaskEmpty.vue'
import TaskItem from '@/components/TaskItem.vue'
import TaskTitle from '@/components/TaskTitle.vue'
import { useRouter } from 'vue-router'


const router = useRouter()
const loading = ref(false)
const api = new Api()
const newTask = ref('')
const tasks = ref<Task[]>([])

const pendingTasks = computed(() => {
  return tasks.value.filter((task: { completed: boolean }) => !task.completed).length
})

const isEmpty = computed(() => tasks.value.length === 0)


const addTask = () => {
  const newTask = document.getElementById('new_task') as HTMLInputElement
  if (newTask.value) {
    const task: Task = {
      title: newTask.value,
      description: 'no description',
      due_at: formatearFecha(new Date())
    }
    newTask.value = ''
    api.createTask(task).then(() => {
      toast('Task created', { type: 'success', delay: 100, autoClose: true })
      tasks.value.push(task)
    }).catch((error) => {
      console.log('Error', error)
    })
  }
}


const deleteTask = async (id: string) => {
  const index = tasks.value.findIndex((task: { uuid: string }) => task.uuid === id)
  tasks.value.splice(index, 1)

  await api.deleteTask(id).then(() => {
    toast('Task deleted', { type: 'success', delay: 100, autoClose: true })
  }).catch((error) => {
    console.log('Error', error)
  })
}

const uploadAttachment = (id: string) => {
  const file = document.getElementById('file') as HTMLInputElement
  file.click()
  file.addEventListener('change', async (event) => {
    const file = (event.target as HTMLInputElement).files?.[0]
    if (file) {
      await api.uploadAttachment(id, file).then(() => {
        toast('Attachment uploaded', { type: 'success', delay: 100, autoClose: true })
      }).catch((error) => {
        console.log('Error', error)
      })
    }
  })
}

const downloadAttachment = (id: string, filename: string) => {
  api.downloadAttachment(id, filename).catch((error) => {
    toast('Error downloading attachment', { type: 'error', delay: 100, autoClose: true })
  })
}

const toggleTask = async (id: string) => {
  const index = tasks.value.findIndex((task: { uuid: string }) => task.uuid === id)
  const isCompleted = !tasks.value[index].completed
  tasks.value[index].completed = isCompleted
  await api.toggleTask(id, isCompleted).then(() => {
    toast('Task status  changed', { type: 'success', delay: 100, autoClose: true })
  }).catch((error) => {
    console.log('Error', error)
  })
}

const showTask = (id: string) => {
  router.push({ name: 'task-detail', params: { id } })
}


onMounted(async () => {
  loading.value = true
  await api.getTasks().then(({ data }) => {
    tasks.value = data
    loading.value = false
  }).catch((error) => {
    console.log('Error', error)
  })
})

</script>

<template>
  <div class="bg-[#fff] rounded-[25px] p-[30px] [box-shadow:0px_0px_40px_0px_rgba(0,0,0,0.1)]">

    <!-- title -->
    <TaskTitle title="Mis Tareas" />
    <!-- form -->
    <div class="relative mt-0 mx-0 mb-[30px]">
      <!-- File input -->
      <input type="file" id="file" class="hidden" />

      <input type="text" id="new_task" v-model="newTask" @keyup.enter="addTask"
             class="w-full h-[50px] p-[15px] bg-[#f3f3f3] text-[#333] border-[1px] border-[transparent] rounded-[10px] [transition:border_0.3s_linear]"
             placeholder="Nueva tarea"
             required />
    </div>

    <!-- Lista de tareas  -->
    <div class="px-[10px] py-0 ">
      <ul v-if="tasks.length > 0">
        <TaskItem
          v-for="task in tasks"
          :key="task.uuid" :task="task"
          @delete-task="deleteTask"
          @upload-attachment="uploadAttachment"
          @download-attachment="downloadAttachment"
          @toggle-task="toggleTask"
          @show-task="showTask"
        />
      </ul>
      <TaskEmpty v-if="isEmpty && !loading" />
      <TaskLoading v-if="loading" />
    </div>
    <TaskFooter :pendingTasks="pendingTasks" />
  </div>

</template>

<style scoped>

</style>