<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import ExpenseForm from './Partials/ExpenseForm.vue'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'
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
            <BreadcrumbLink :href="route('expenses.index')">
              المصروفات
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
      :route="route('expenses.store')"
      @success="toast({ title: 'تم إنشاء المصروف' })"
    >
      <ExpenseForm :networks="networks" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
