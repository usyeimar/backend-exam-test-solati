import type { User, Attachment } from '@/types'

export type Task = {
  id: number;
  uuid: string;
  object: string;
  title: string;
  description: string;
  completed: boolean;
  attachments: Attachment[];
  user: User;
  due_at: Date;
  completed_at: Date;
  created_at: Date;
  updated_at: Date;

}

