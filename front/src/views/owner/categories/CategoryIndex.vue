<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'


const router = useRouter()
// const categories = ref([])
const loading = ref(true)
const error = ref(null)

const categories = ref({
  // data: [],
  // meta: {
  //   current_page: 1,
  //   last_page: 1
  // }
})
// axios の利点
// 自動的に JSON.parse() してくれる
// HTTPステータスでエラーハンドリングがしやすい
const fetchCategories = async () => {
    loading.value = true
    error.value = null
  try {
    const res = await apiClient.get('/owner/categories');
    console.log(res);
    // console.log(res.data);
    categories.value = res.data // 
  } catch (error) {
    alert('カテゴリ一覧の取得に失敗しました')
      error.value = '商品の取得に失敗しました'
  }finally {
    loading.value = false
  }
}

const confirmDelete = async (id) => {
  if (confirm('本当に削除しますか？')) {
    try {
      await apiClient.delete(`/owner/categories/${id}`)
      await fetchCategories()
    } catch (error) {
      alert('削除に失敗しました')
      console.error(error)
    }
  }
}
onMounted(() => {
    // fetchPage()

  fetchCategories()
})
 </script>


<!-- <script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const loading = ref(true)
const error = ref(null)

const categories = ref({
  data: [],
  current_page: 1,
  last_page: 1
})

const fetchPage = async (page = 1) => {
  loading.value = true
  error.value = null
  try {
    const res = await apiClient.get(`/owner/categories?page=${page}`)
    categories.value = res.data
  } catch (e) {
    error.value = 'カテゴリーの取得に失敗しました'
  } finally {
    loading.value = false
  }
} -->

<!-- const confirmDelete = async (id) => {
  if (confirm('本当に削除しますか？')) {
    try {
      await apiClient.delete(`/owner/categories/${id}`)
      alert('カテゴリーを削除しました')
      categories.value.data = categories.value.data.filter(c => c.id !== id)
    } catch (error) {
      alert('削除に失敗しました')
      console.error(error)
    }
  }
}

onMounted(() => {
  fetchPage()
})
</script> -->

<template>
 <div>
    <h1>
      <span class="active-tab">カテゴリ一覧</span>
      /
      <RouterLink to="/owner/series">シリーズ一覧</RouterLink>
    </h1>
    <router-link to="/owner/categories/create">新規作成</router-link>
        <div v-if="loading">
            <div class="loader"></div>
        </div>
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
        <div v-else>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>名前</th>
          <th>表示順</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in categories" :key="category.id">
          <td>{{ category.id }}</td>
          <td>{{ category.name }}</td>
          <td>{{ category.sort_order }}</td>
          <td>
            <router-link :to="`/owner/categories/${category.id}/edit`">編集</router-link>
           <button @click="confirmDelete(category.id)">削除</button>
                  <p v-if="message">{{ message }}</p>
      <p v-if="errorMessage" style="color: red">{{ errorMessage }}</p>
          </td>
        </tr>
      </tbody>
    </table>
    </div>
  </div>
</template>

<style scoped>
.active-tab {
  border-bottom: 3px solid #338fe5;
  font-weight: bold;
  padding-bottom: 2px;
}

table {
  width: 500px;           /* テーブル全体の幅を固定（必要に応じて調整） */
  max-width: 100%;
  margin: 2em auto 0 auto;
  border-collapse: collapse;
  table-layout: fixed;    /* 列幅を固定 */
}
th, td {
  border: 1px solid #ddd;
  padding: 6px;
  text-align: center;
  word-break: break-all;
}
th:nth-child(1), td:nth-child(1) { width: 50px; }   /* ID */
th:nth-child(2), td:nth-child(2) { width: 200px; }  /* 名前 */
th:nth-child(3), td:nth-child(3) { width: 80px; }   /* 表示順 */
th:nth-child(4), td:nth-child(4) { width: 150px; }   /* 操作 */

button {
  background-color: #f44336; /* 赤色 */
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
}
button:hover {
  background-color: #d32f2f; /* 濃い赤色 */
}
</style>