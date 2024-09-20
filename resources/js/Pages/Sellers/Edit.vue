<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { Button } from '@/Components/ui/button'
import {
  FormControl,
  FormDescription,
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
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Input } from '@/Components/ui/input'
import { toast } from '@/Components/ui/toast'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { Region, User } from '@/types'

const props = defineProps<{
  seller: User
  regions: Array<Region>
}>()

const formSchema = toTypedSchema(
  z.object({
    region: z.string({ message: 'هذا الحقل مطلوب' }),
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    username: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'اسم المستخدم يجيب ان يكون حرفين على الاقل' }),
    password: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(4, { message: 'كلمة المرور يجب ان تكون 4 احرف على الاقل' })
      .optional(),
    contact_info: z.string().optional(),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    region: String(props.seller.region.id),
    name: props.seller.name,
    username: props.seller.username,
    contact_info: props.seller.contact_info ?? undefined,
    notes: props.seller.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('sellers.update', props.seller.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل نقطة البيع' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Sellers" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ seller.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField v-slot="{ componentField }" name="name">
                  <FormItem>
                    <FormLabel>اسم نقطة البيع</FormLabel>
                    <FormControl>
                      <Input
                        type="text"
                        placeholder="حجاج"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="region">
                  <FormItem>
                    <FormLabel>المنطقة</FormLabel>
                    <FormControl>
                      <Select v-bind="componentField">
                        <SelectTrigger>
                          <SelectValue placeholder="اختر منطقة" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectGroup>
                            <SelectLabel>المناطق</SelectLabel>
                            <SelectItem
                              v-for="region in regions"
                              :key="region.id"
                              :value="String(region.id)"
                            >
                              {{ region.name }}
                            </SelectItem>
                          </SelectGroup>
                        </SelectContent>
                      </Select>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="username">
                  <FormItem>
                    <FormLabel>اسم المستخدم</FormLabel>
                    <FormControl>
                      <Input
                        type="text"
                        placeholder="hajaj"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormDescription>
                      سيتم استخدام هذا الاسم في عملية تسجيل الدخول.
                    </FormDescription>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="password">
                  <FormItem>
                    <FormLabel>كلمة المرور</FormLabel>
                    <FormControl>
                      <Input
                        type="password"
                        placeholder="********"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormDescription>
                      سيتم استخدامها في عملية تسجيل الدخول.
                    </FormDescription>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="contact_info">
                  <FormItem class="md:col-span-2">
                    <FormLabel>بيانات التواصل</FormLabel>
                    <FormControl>
                      <Textarea v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="notes">
                  <FormItem class="md:col-span-2">
                    <FormLabel>ملاحظات</FormLabel>
                    <FormControl>
                      <Textarea v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </div>
              <Button type="submit"> تعديل </Button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
