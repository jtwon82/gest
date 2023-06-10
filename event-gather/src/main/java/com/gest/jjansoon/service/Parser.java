package com.gest.jjansoon.service;

import java.util.Map;

import org.springframework.beans.factory.annotation.Autowired;

import com.gest.jjansoon.service.dao.ContentDao;

public interface Parser {
	
	public Parser getParser();
	
	public void parse(Map<String, String> item) throws Exception;
}
