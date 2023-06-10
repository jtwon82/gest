package com.gest.jjansoon.model;

import java.util.List;

public class smartstoreRootVo {
	private List<smartstoreContentsVo> contents;
	private String page;
	private String size;
	private String totalElements;
	private String totalPages;
	
	public List<smartstoreContentsVo> getContents() {
		return contents;
	}
	public void setContents(List<smartstoreContentsVo> contents) {
		this.contents = contents;
	}
	public String getPage() {
		return page;
	}
	public void setPage(String page) {
		this.page = page;
	}
	public String getSize() {
		return size;
	}
	public void setSize(String size) {
		this.size = size;
	}
	public String getTotalElements() {
		return totalElements;
	}
	public void setTotalElements(String totalElements) {
		this.totalElements = totalElements;
	}
	public String getTotalPages() {
		return totalPages;
	}
	public void setTotalPages(String totalPages) {
		this.totalPages = totalPages;
	}
	
}

