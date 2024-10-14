<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import UserForm from './Partials/UserForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'

import { Network } from '@/types'

defineProps<{
  networks: Array<Network>
}>()

const initialValues = {
  percentage: 10,
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
            <BreadcrumbPage>إنشاء</BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('users.store')"
      class="mt-4"
    >
      <template #default="{ form }">
        <UserForm :role="form.values.role" :networks="networks" />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
