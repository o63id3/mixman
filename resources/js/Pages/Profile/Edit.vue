<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'

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
      <Breadcrumb class="mx-2 md:mx-0">
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink href="/"> الرئيسة </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage> حسابي </BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <div class="space-y-12">
      <UpdateProfileInformationForm />
      <UpdatePasswordForm />
    </div>
  </AuthenticatedLayout>
</template>
