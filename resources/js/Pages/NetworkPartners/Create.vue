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
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Input } from '@/Components/ui/input'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import { CardTitle } from '@/Components/ui/card'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { AlertCircle } from 'lucide-vue-next'
import { Network, User } from '@/types'

const props = defineProps<{
  network: Network
  partners: Array<User>
  remainingShare: number
}>()

const formSchema = toTypedSchema(
  z.object({
    user_id: z.string({ message: 'هذا الحقل مطلوب' }),
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

      <FormField v-slot="{ componentField }" name="user_id">
        <FormItem>
          <FormLabel>الشريك</FormLabel>
          <FormControl>
            <Select v-bind="componentField">
              <SelectTrigger>
                <SelectValue placeholder="اختر الشريك" />
              </SelectTrigger>
              <SelectContent>
                <SelectGroup>
                  <SelectItem
                    v-for="partner in partners"
                    :key="partner.id"
                    :value="String(partner.id)"
                  >
                    {{ partner.name }}
                  </SelectItem>
                </SelectGroup>
              </SelectContent>
            </Select>
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
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
