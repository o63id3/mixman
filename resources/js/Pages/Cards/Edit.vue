<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { formSchema } from './definitions'
import CardForm from './Partials/CardForm.vue'

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
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import { DeleteLink, SecondaryLink } from '@/Components/links'

import { Card } from '@/types'

const props = defineProps<{
  card: Card
}>()

const initialValues = {
  name: props.card.name,
  price_for_consumer: props.card.price_for_consumer,
  notes: props.card.notes,
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
            <BreadcrumbLink :href="route('cards.index')">
              الكروت
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>
              {{ card.name }}
              <Badge
                variant="outline"
                class="mr-auto sm:ml-0"
                :class="[card.active ? 'text-green-500' : 'text-red-500']"
              >
                {{ card.active ? 'فعال' : 'معطل' }}
              </Badge>
            </BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('cards.update', card.id)"
    >
      <template #buttons>
        <DeleteLink
          v-if="card.active"
          :href="route('cards.deactivate', card.id)"
          @success="toast({ title: 'تم تعطيل الكرت' })"
          method="POST"
        >
          تعطيل
        </DeleteLink>
        <SecondaryLink
          v-else
          method="DELETE"
          :href="route('cards.activate', card.id)"
          @success="toast({ title: 'تم تفعيل الكرت' })"
        >
          تفعيل
        </SecondaryLink>
      </template>

      <CardForm />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
