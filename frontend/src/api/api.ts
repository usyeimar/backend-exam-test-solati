import http from '@/services/http'
import type { Attachment, Task } from '@/types'
import { saveAs } from 'file-saver'


export class Api {

  async getTasks(): Promise<{
    data: Task[],
    meta: any,
    links: any
  }> {
    const { data } = await http.get('/api/v1/tasks')

    return data


  }

  async getTask(id: number): Promise<{
    data: any
  }> {
    const { data } = await http.get(`/api/v1/tasks/${id}`)

    return data
  }

  async createTask(payload: Task): Promise<{
    data: any
  }> {
    const { data } = await http.post('/api/v1/tasks', payload)

    return data
  }

  async updateTask(id: number, payload: {
    title: string,
    description: string
  }): Promise<{
    data: any
  }> {
    const { data } = await http.put(`/api/v1/tasks/${id}`, payload)

    return data
  }

  async deleteTask(id: string): Promise<void> {
    await http.delete(`/api/v1/tasks/${id}`)
  }

  async uploadAttachment(id: string, file: File): Promise<void> {
    const formData = new FormData()
    formData.append('attachment', file)
    formData.append('task_uuid', id)

    const { data } = await http.postForm(`/api/v1/attachments/upload`, formData)
  }

  async downloadAttachment(id: string, filename: string): Promise<void> {
    const { data, headers } = await http.get(`/api/v1/attachments/download/${id}`, { responseType: 'blob' })
    const blob = new Blob([data], { type: headers['content-type'] })
    saveAs(blob, filename)
  }

  async toggleTask(id: string, completed = false): Promise<void> {
    await http.patch(`/api/v1/tasks/${id}`, { completed })
  }

}