<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import { CardTitle } from '@/Components/ui/card'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'
import Combobox from '@/Components/combobox/Combobox.vue'

import { AlertCircle } from 'lucide-vue-next'
import { Network, User } from '@/types'

const props = defineProps<{
  network: Network
  partners: Array<User>
  remainingShare: number
}>()

const formSchema = toTypedSchema(
  z.object({
    user_id: z.number({ message: 'هذا الحقل مطلوب' }),
    share: z
      .number({ message: 'هذا الحقل مطلوب' })
      .min(1, { message: 'يجب أن يكون أكبر من 1' })
      .max(100, { message: 'يجب أن يكون أقل من 100' }),
  }),
)

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الشبكات',
    route: route('networks.index'),
  },
  {
    label: props.network.name,
    route: route('networks.edit', props.network.id),
  },
  {
    label: 'الشركاء',
  },
  {
    label: 'إضافة',
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
    </template>

    <Alert class="rounded-none sm:rounded-xl" variant="destructive">
      <AlertCircle class="h-4 w-4" />
      <AlertTitle>انتباه!</AlertTitle>
      <AlertDescription>
        <div v-if="remainingShare">
          نسبة الحصص المتبقية في
          <span class="font-bold">
            {{ network.name }}
          </span>
          هي
          <span class="font-bold">{{ Math.round(remainingShare) }}%</span>
        </div>
        <span v-else>لا يوجد حصص متبقية!</span>
      </AlertDescription>
    </Alert>

    <CreateFormLayout
      class="mt-2"
      :form-schema="formSchema"
      :route="route('network.partners.store', network.id)"
    >
      <template #title>
        <CardTitle>
          إضافة شريك
          <span class="text-xs font-normal tracking-wide">
            ({{ network.name }})
          </span>
        </CardTitle>
      </template>

      <template #default="{ form }">
        <FormField name="user_id">
          <FormItem>
            <FormLabel>الشريك</FormLabel>
            <FormControl>
              <Combobox
                :options="
                  partners.map((partner) => ({
                    value: partner.id,
                    label: partner.name,
                  }))
                "
                choose-title="اختر الشريك..."
                search-placeholder="ابحث عن شريك"
                :selected="form.values.user_id"
                @select="
                  (selected: any) =>
                    form.setFieldValue('user_id', selected.value)
                "
              />
            </FormControl>
            <FormMessage />
          </FormItem>
        </FormField>
        <FormField v-slot="{ componentField }" name="share">
          <FormItem>
            <FormLabel>الحصة</FormLabel>
            <FormControl>
              <Input type="number" v-bind="componentField" />
            </FormControl>
            <FormMessage />
          </FormItem>
        </FormField>
      </template>
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
