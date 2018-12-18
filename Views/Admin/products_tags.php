<?php $this->Partial("navigation") ?>
<div id="adminPages">
    <div id="adminContent">
        <div id="adminInnerContent">
            <h3><a href="/admin/products">◄ Products</a></h3>
            <h2>Tags</h2>
            <div class="adminTable">
                <table>
                    <tr>
                        <th colspan="2" width="40%">Tag Name</th>
                        <th width="30%">Set Image</th>
                        <th width="10%">Remove Image</th>
                        <th width="10%"></th>
                    </tr>
                    <tbody v-for="item in Result">
                        <tr>
                            <td width="10%" v-on:click="Show('sub_' + item.id)"><div v-bind:style="{ 'background-image': 'url(' + item.str_image_path + ')' }" class="image size-50"></div></td>
                            <td width="30%" v-on:click="Show('sub_' + item.id)">{{item.str_tag}}</td>
                            <td><input type="file" v-on:change="UploadImage(item.str_tag, item.id)" v-bind:id="'image_' + item.id" /></td>
                            <td><button v-on:click="DeleteImage(item.str_tag)">Remove</button></td>
                            <td><button v-on:click="DeleteTags(item.str_tag)">Delete</button></td>
                        </tr>
                        <tr v-for="subItem in item.products" 
                            v-bind:class="'gray sub_' + item.id" 
                            v-on:click="Redirect(subItem.id)" 
                            style="display:none">
                            <td></td>
                            <td colspan="4">({{subItem.str_code}}) {{subItem.str_brand}} - {{subItem.str_name}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="/Resources/js/admin/products_tags.js" async></script>