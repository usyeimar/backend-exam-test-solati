import http from '@/services/http'

export class Api {

  async getAllTasks(): Promise<{
    data: any,
    meta: any,
    links: any
  }> {
    const { data } = await http.get('/v1/tasks')

    return data


  }

  async getTask(id: number): Promise<{
    data: any
  }> {
    const { data } = await http.get(`/v1/tasks/${id}`)

    return data
  }

  async createTask(payload: {
    title: string,
    description: string,
    due_at: string
  }): Promise<{
    data: any
  }> {
    const { data } = await http.post('/v1/tasks', payload)

    return data
  }

  async updateTask(id: number, payload: {
    title: string,
    description: string
  }): Promise<{
    data: any
  }> {
    const { data } = await http.put(`/v1/tasks/${id}`, payload)

    return data
  }

  async deleteTask(id: number): Promise<void> {
    await http.delete(`/v1/tasks/${id}`)
  }

}