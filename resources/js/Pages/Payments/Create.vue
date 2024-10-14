<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'

import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import PaymentForm from './Partials/PaymentForm.vue'

import { User } from '@/types'

defineProps<{
  users: Array<User>
}>()
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
            <BreadcrumbLink :href="route('payments.index')">
              الدفعات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>إضافة</BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <CreateFormLayout
      :form-schema="formSchema"
      :route="route('payments.store')"
      btn-title="إضافة"
    >
      <template #default="{ form }">
        <PaymentForm
          :users="users"
          :selected="form.values.user_id"
          @select="
            (selected: number) => form.setFieldValue('user_id', selected)
          "
        />
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
