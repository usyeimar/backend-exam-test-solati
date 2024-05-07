<script setup lang="ts">

import { EyeIcon, TrashIcon,PencilIcon } from '@heroicons/vue/24/solid'
import type { Task } from '@/types'

defineProps<{
  task: Task
}>()

const emits = defineEmits([
  'delete-task',
  'toggle-task',
  'show-task',
  'edit-task'
])
</script>

<template>
  <li class="flex justify-between mt-0 mx-0 mb-[20px]">
    <div class="flex items-center gap-1">
      <input type="checkbox"
             @change="emits('toggle-task', task.uuid)"
              :checked="task.completed"
             class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
      <p class="text-lg text-gray-900 dark:text-white"
      :class="task.completed ? 'line-through text-gray-400 dark:text-gray-300' : 'text-gray-900 dark:text-white'"
      >
        {{ task.title }}
      </p>
    </div>

    <div class="flex justify-end gap-2">
      <TrashIcon class="h-[20px] w-[20px] text-gray-400 cursor-pointer" @click="emits('delete-task', task.uuid)" />
      <EyeIcon class="h-[20px] w-[20px] text-gray-400 cursor-pointer" @click="emits('show-task', task.uuid)" />
      <PencilIcon class="h-[20px] w [20px] text-gray-400 cursor-pointer" @click="emits('edit-task', task.uuid)" />
    </div>
  </li>

</template>

<style scoped>

</style>