<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
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
import { Network, User } from '@/types'

import { AlertCircle } from 'lucide-vue-next'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import { toast } from '@/Components/ui/toast'

defineProps<{
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
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        إضافة شريك
        <span class="text-xs font-normal tracking-wide">
          ({{ network.name }})
        </span>
      </h2>
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
      route="network.partners.store"
      @success="toast({ title: 'تم إضافة الشريك' })"
    >
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
