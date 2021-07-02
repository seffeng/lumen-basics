### 更新日志

* 2021-06-21

  * 修改排序方法

  ```shell
  # 原请求方式：/?orderby={"key":"sort","value":"desc"}
  # 修改后：/?orderby=-sort
  
  # 说明
  ## orderBy=-sort；代表 sort 降序
  ## orderBy=sort；代表 sort 升序
  ## orderBy=-id,sort；代表 id 降序，sort 升序
  ```

---


