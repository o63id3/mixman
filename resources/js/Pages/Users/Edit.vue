<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'

import { toast } from '@/Components/ui/toast'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { Badge } from '@/Components/ui/badge'
import { DeleteLink, SecondaryLink } from '@/Components/links'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { Network, User } from '@/types'

const props = defineProps<{
  user: User
  networks: Array<Network>
  can: {
    update: boolean
    delete: boolean
    activate: boolean
    deactivate: boolean
  }
}>()

const initialValues = {
  name: props.user.name,
  username: props.user.username,
  telegram: props.user.telegram,
  role: props.user.role,
  network_id: props.user.network ? String(props.user.network?.id) : undefined,
  percentage: props.user.percentage,
  contact_info: props.user.contact_info,
}
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <Breadcrumb>
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('dashboard')">
              الرئيسة
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('users.index')">
              المستخدمين
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>
              {{ user.name }}
              <Badge
                variant="outline"
                class="mr-auto sm:ml-0"
                :class="[user.active ? 'text-green-500' : 'text-red-500']"
              >
                {{ user.active ? 'فعال' : 'معطل' }}
              </Badge>
            </BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('users.update', user.id)"
      @success="toast({ title: 'تم تعديل المستخدم' })"
      :can-update="can.update"
    >
      <template v-if="can.activate || can.deactivate" #buttons>
        <DeleteLink
          v-if="user.active && can.deactivate"
          :href="route('users.deactivate', user.id)"
          @success="toast({ title: 'تم تعطيل الحساب' })"
          method="POST"
        >
          تعطيل
        </DeleteLink>
        <SecondaryLink
          v-else-if="can.activate"
          method="DELETE"
          :href="route('users.activate', user.id)"
          @success="toast({ title: 'تم تفعيل الحساب' })"
        >
          تفعيل
        </SecondaryLink>
      </template>

      <template #default="{ form }">
        <UserForm
          :disabled="!can.update"
          :role="form.values.role"
          :networks="networks"
        />
      </template>
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
