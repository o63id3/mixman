<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import NetworkForm from './Partials/NetworkForm.vue'

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
            <BreadcrumbLink :href="route('networks.index')">
              الشبكات
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
      :route="route('networks.store')"
      @success="toast({ title: 'تم إنشاء الشبكة' })"
    >
      <NetworkForm />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
