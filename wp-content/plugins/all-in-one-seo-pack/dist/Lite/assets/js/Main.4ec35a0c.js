import"./WpTable.e2f412d1.js";import"./default-i18n.3a91e0e5.js";import"./constants.0d8c074c.js";import{n as o}from"./_plugin-vue2_normalizer.61652a7c.js";import"./index.ec9852b3.js";import{R as r,a as i}from"./RequiresUpdate.5342b97f.js";import"./SaveChanges.e40a9083.js";import{a as e,b as a,m as p}from"./vuex.esm.8fdeb4b6.js";import{C as c}from"./Index.3eb7dd13.js";import{b as l}from"./Header.df1a3a84.js";import m from"./Overview.0fb194dd.js";import"./helpers.de7566d0.js";import"./attachments.437fe1f4.js";import"./cleanForSlug.51ef7354.js";import"./isArrayLikeObject.9b4b678d.js";import"./Caret.d93b302e.js";import"./html.14f2a8b9.js";import"./Index.5f7ddb17.js";import"./RequiresUpdate.72442782.js";/* empty css             */import"./params.597cd0f5.js";import"./LicenseKeyBar.f7493613.js";import"./LogoGear.16108a75.js";import"./Logo.8785cc9f.js";import"./Support.85587a91.js";import"./Tabs.a309f2c7.js";import"./TruSeoScore.339d22e1.js";import"./Information.93f80cbf.js";import"./Slide.15a07930.js";import"./Exclamation.fd45a7b0.js";import"./Url.c71d5763.js";import"./Gear.184e0c65.js";import"./AnimatedNumber.932b583a.js";import"./index.4b67d3e2.js";import"./_commonjsHelpers.f84db168.js";import"./Blur.f36c594d.js";import"./Row.830f6397.js";import"./Card.db2ec99d.js";import"./Tooltip.68a8a92b.js";import"./InternalOutbound.e736afb6.js";import"./DonutChartWithLegend.72361d7c.js";import"./SeoSiteScore.29a89953.js";import"./Row.2f03c6a0.js";import"./RequiredPlans.3ea0b33e.js";import"./Index.21aaf27c.js";const _={};var u=function(){var t=this,n=t._self._c;return n("div")},d=[],f=o(_,u,d,!1,null,null,null,null);const v=f.exports,g={};var $=function(){var t=this,n=t._self._c;return n("div")},h=[],x=o(g,$,h,!1,null,null,null,null);const k=x.exports,A={};var R=function(){var t=this,n=t._self._c;return n("div")},b=[],y=o(A,R,b,!1,null,null,null,null);const S=y.exports,M={};var P=function(){var t=this,n=t._self._c;return n("div")},T=[],C=o(M,P,T,!1,null,null,null,null);const F=C.exports;const L={components:{CoreMain:c,CoreProcessingPopup:l,DomainsReport:v,LinksReport:k,Overview:m,PostReport:S,Settings:F},mixins:[r,i],data(){return{strings:{pageName:this.$t.__("Link Assistant",this.$td)}}},computed:{...e("linkAssistant",["suggestionsScan"]),excludedTabs(){const s=(this.$addons.isActive("aioseo-link-assistant")?this.getExcludedUpdateTabs("aioseo-link-assistant"):this.getExcludedActivationTabs("aioseo-link-assistant"))||[];return s.push("post-report"),s}},methods:{...a("linkAssistant",["toggleProcessingPopup"]),...p("linkAssistant",["pollSuggestionsScan","getMenuData"])},mounted(){this.$bus.$on("changes-saved",()=>{this.getMenuData()}),this.$isPro&&this.suggestionsScan.percent!==100&&this.$addons.isActive("aioseo-link-assistant")&&!this.$addons.requiresUpgrade("aioseo-link-assistant")&&this.$addons.hasMinimumVersion("aioseo-link-assistant")&&this.pollSuggestionsScan()}};var U=function(){var t=this,n=t._self._c;return n("div",{staticClass:"aioseo-link-assistant"},[n("core-main",{attrs:{"page-name":t.strings.pageName,"exclude-tabs":t.excludedTabs,showTabs:t.$route.name!=="post-report"}},[n(t.$route.name,{tag:"component"})],1)],1)},q=[],D=o(L,U,q,!1,null,null,null,null);const Mt=D.exports;export{Mt as default};
