<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'

import { toast } from '@/Components/ui/toast'
import { Badge } from '@/Components/ui/badge'
import { DeleteLink, SecondaryLink } from '@/Components/links'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

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

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'المستخدمين',
    route: route('users.index'),
  },
  {
    label: props.user.name,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex items-center gap-2">
        <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
        <Badge
          variant="outline"
          class="mr-auto sm:ml-0"
          :class="[user.active ? 'text-green-500' : 'text-red-500']"
        >
          {{ user.active ? 'فعال' : 'معطل' }}
        </Badge>
      </div>
    </template>

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('users.update', user.id)"
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
